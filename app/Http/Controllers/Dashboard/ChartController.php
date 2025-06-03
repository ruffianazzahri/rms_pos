<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getOrdersChartData(Request $request)
    {
        $period = $request->input('period', 'monthly'); // default monthly

        // Buat query builder
        $query = DB::table('orders')
            ->where('order_status', 'completed')
            ->whereNotNull('order_date')
            ->whereNotNull('total');

        // Pilih kolom dan grouping sesuai period
        switch ($period) {
            case 'daily':
                $query->selectRaw('DATE(order_date) as period_label, SUM(total) as total')
                    ->groupBy('period_label')
                    ->orderBy('period_label');
                break;

            case 'weekly':
                // WEEK with mode 1 = ISO week starting Monday
                $query->selectRaw('YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total')
                    ->groupBy('year', 'week')
                    ->orderBy('year')
                    ->orderBy('week');
                break;

            case 'monthly':
                $query->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period_label, SUM(total) as total')
                    ->groupBy('period_label')
                    ->orderBy('period_label');
                break;

            case 'yearly':
                $query->selectRaw('YEAR(order_date) as period_label, SUM(total) as total')
                    ->groupBy('period_label')
                    ->orderBy('period_label');
                break;

            default:
                // fallback monthly
                $query->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period_label, SUM(total) as total')
                    ->groupBy('period_label')
                    ->orderBy('period_label');
                break;
        }

        $results = $query->get();

        $seriesData = [];

        if ($period === 'weekly') {
            foreach ($results as $row) {
                $label = "Week {$row->week}, {$row->year}";
                $seriesData[] = ['x' => $label, 'y' => (float) $row->total];
            }
        } else {
            foreach ($results as $row) {
                // period_label sudah sesuai string
                $label = $row->period_label;
                $seriesData[] = ['x' => $label, 'y' => (float) $row->total];
            }
        }

        return response()->json([
            'series' => [
                [
                    'name' => 'Total Orders',
                    'data' => $seriesData,
                ]
            ]
        ]);
    }
}
