<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OmzetController extends Controller
{
    public function showForm()
    {
        return view('omzet.print_form');
    }

    public function print(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly,yearly',
        ]);

        $period = $request->period;
        $now = \Carbon\Carbon::now();

        $query = DB::table('orders')
            ->where('order_status', 'completed')
            ->whereNotNull('order_date')
            ->whereNotNull('total');

        if ($period === 'daily') {
            // Filter hanya hari ini
            $query->whereDate('order_date', $now->toDateString());

            $query->selectRaw("
                DATE_FORMAT(order_date, '%Y-%m-%d %H:00:00') AS label,  -- label per jam
                DATE_FORMAT(order_date, '%Y-%m-%d %H:00:00') AS sort_date,
                SUM(total) AS total
            ")
                ->groupBy('label', 'sort_date')
                ->orderBy('sort_date');
        } elseif ($period === 'weekly') {
            // Filter hanya minggu-minggu di bulan ini
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            $query->whereBetween('order_date', [$startOfMonth, $endOfMonth]);

            // Ambil tahun dan nomor minggu dalam bulan sekarang sebagai label dan sort
            // Tapi MySQL WEEK() default hitung minggu tahun, kita pakai WEEK(order_date, 1) untuk mode ISO
            // Tambahkan juga bulan agar bisa tampil "Minggu 1, Bulan Juni 2025"
            $query->selectRaw("
            WEEK(order_date, 1) - WEEK(DATE_SUB(order_date, INTERVAL DAYOFMONTH(order_date)-1 DAY), 1) + 1 AS week_of_month,
            DATE_FORMAT(order_date, '%Y-%m') AS month_label,
            MIN(order_date) AS min_date,
            SUM(total) AS total
            ");

            $query->groupBy('week_of_month', 'month_label')
                ->orderBy('month_label')
                ->orderBy('week_of_month');

            $dataRaw = $query->get();

            // Ubah data supaya label jadi "Minggu X, Bulan Y Tahun Z"
            $data = $dataRaw->map(function ($item) {
                $monthYear = \Carbon\Carbon::createFromFormat('Y-m', $item->month_label);
                $item->label = "Minggu {$item->week_of_month}, Bulan " . $monthYear->translatedFormat('F Y');
                $item->sort_date = $item->min_date;
                return $item;
            });

            $totalOmzet = $data->sum('total');

            return view('omzet.print_result', compact('data', 'period', 'totalOmzet'));
        } elseif ($period === 'yearly') {
            // Ambil semua data per tahun
            $query->selectRaw("
                YEAR(order_date) AS label,
                MIN(order_date) AS sort_date,
                SUM(total) AS total
            ")
            ->groupBy('label')
            ->orderBy('label');
        } else { // monthly
            // Filter hanya tahun ini
            $startOfYear = $now->copy()->startOfYear();
            $endOfYear = $now->copy()->endOfYear();

            $query->whereBetween('order_date', [$startOfYear, $endOfYear]);

            $query->selectRaw("
            DATE_FORMAT(order_date, '%Y-%m') AS label,
            MIN(order_date) AS sort_date,
            SUM(total) AS total
        ")
                ->groupBy('label')
                ->orderBy('sort_date');
        }

        $data = $query->get();
        $totalOmzet = $data->sum('total');

        return view('omzet.print_result', compact('data', 'period', 'totalOmzet'));
    }


    private function getDateFormat($period)
    {
        return match ($period) {
            'daily'   => "DATE(order_date)",
            'weekly'  => "YEAR(order_date), WEEK(order_date)",
            'monthly' => "DATE_FORMAT(order_date, '%Y-%m')",
        };
    }

    private function getRawDateForSorting($period)
    {
        return match ($period) {
            'daily' => "DATE(order_date)",
            'weekly' => "MIN(DATE(order_date))", // gunakan tanggal awal minggu
            'monthly' => "STR_TO_DATE(DATE_FORMAT(order_date, '%Y-%m-01'), '%Y-%m-%d')",
        };
    }
}
