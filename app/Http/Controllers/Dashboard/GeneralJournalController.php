<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class GeneralJournalController extends Controller
{
    // public function index(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $rawQuery = "
    //         SELECT * FROM (
    //             -- General Journal Entries
    //             SELECT
    //                 gj.id,
    //                 gj.date AS date,
    //                 gj.account,
    //                 gj.debit,
    //                 gj.credit,
    //                 gj.description,
    //                 gj.order_id,
    //                 NULL AS invoice_no,
    //                 NULL AS order_date,
    //                 gj.image AS image
    //             FROM general_journal gj
    //             WHERE MONTH(gj.date) = ? AND YEAR(gj.date) = ?

    //             UNION ALL

    //             -- Pendapatan dari Orders
    //             SELECT
    //                 NULL AS id,
    //                 DATE(o.order_date) AS date,
    //                 'Pendapatan (Orders)' AS account,
    //                 0 AS debit,
    //                 SUM(o.sub_total) AS credit,
    //                 'Omzet dari Pembelian Customer (Tidak termasuk pajak)' AS description,
    //                 NULL AS order_id,
    //                 NULL AS invoice_no,
    //                 NULL AS order_date,
    //                 'Otomatis dari data orders' AS image
    //             FROM orders o
    //             WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
    //             GROUP BY DATE(o.order_date)

    //             UNION ALL

    //             -- Pajak Terutang dari Order Taxes
    //             SELECT
    //                 NULL AS id,
    //                 grouped.date,
    //                 'Pajak Barang dan Jasa Tertentu Terhutang' AS account,
    //                 0 AS debit,
    //                 SUM(grouped.tax_amount) AS credit,
    //                 CONCAT('Pajak Restoran yang harus dibayar, dari pendapatan tanggal ', DATE_FORMAT(grouped.date, '%d %M %Y')) AS description,
    //                 NULL AS order_id,
    //                 NULL AS invoice_no,
    //                 NULL AS order_date,
    //                 'Otomatis dari data orders' AS image
    //             FROM (
    //                 SELECT DATE(created_at) AS date, tax_amount
    //                 FROM order_taxes
    //                 WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
    //             ) AS grouped
    //             GROUP BY grouped.date
    //         ) AS combined
    //         ORDER BY date ASC
    //         LIMIT 10;
    //     ";


    //     $journals = DB::select($rawQuery, [$month, $year, $month, $year, $month, $year]);

    //     // Total debit dan credit dari general_journal
    //     $totalGJQuery = "
    //     SELECT
    //         COALESCE(SUM(debit), 0) as total_debit,
    //         COALESCE(SUM(credit), 0) as total_credit
    //     FROM general_journal
    //     WHERE MONTH(date) = ? AND YEAR(date) = ?
    //     ";
    //     $totalsGJ = DB::select($totalGJQuery, [$month, $year])[0];

    //     // Total credit dari orders
    //     $totalOrdersQuery = "
    //     SELECT COALESCE(SUM(total), 0) as total_credit_orders
    //     FROM orders
    //     WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
    // ";
    //     $totalCreditOrders = DB::select($totalOrdersQuery, [$month, $year])[0]->total_credit_orders;

    //     // Total pajak dari order_taxes
    //     $totalTaxQuery = "
    //     SELECT COALESCE(SUM(tax_amount), 0) as total_tax
    //     FROM order_taxes
    //     WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
    // ";
    //     $totalTax = DB::select($totalTaxQuery, [$month, $year])[0]->total_tax;

    //     // Total pemasukan = credit GJ + credit orders + pajak
    //     $totalPemasukan = $totalsGJ->total_credit + $totalCreditOrders + $totalTax;

    //     // Total pengeluaran = debit dari general journal
    //     $totalPengeluaran = $totalsGJ->total_debit;

    //     // Saldo akhir = pemasukan - pengeluaran
    //     $saldoAkhir = $totalPemasukan - $totalPengeluaran;

    //     return view('general_journal.index', [
    //         'journals' => $journals,
    //         'totalDebit' => $totalPengeluaran,
    //         'totalCredit' => $totalPemasukan,
    //         'saldoAkhir' => $saldoAkhir,
    //         'filterMonth' => $month,
    //         'filterYear' => $year,
    //     ]);
    // }

    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil data pendapatan dari tabel orders
        $rawQuery = "
            SELECT
                NULL AS order_id,
                DATE(o.order_date) AS date,
                'Pendapatan (Orders)' AS account,
                0 AS debit,
                SUM(o.total) AS credit,
                'Omzet Penjualan (Termasuk PBJT 10% dan Service Charge)' AS description,
                NULL AS invoice_no,
                NULL AS order_date,
                'Otomatis dari data orders' AS image
            FROM orders o
            WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
            GROUP BY DATE(o.order_date)
            ORDER BY DATE(o.order_date) ASC
            LIMIT 30;
        ";

        $journals = DB::select($rawQuery, [$month, $year]);

        // Total credit dari orders
        $totalOrdersQuery = "
            SELECT COALESCE(SUM(total), 0) as total_credit_orders
            FROM orders
            WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
        ";
        $totalCreditOrders = DB::select($totalOrdersQuery, [$month, $year])[0]->total_credit_orders;

        // Total pajak dari order_taxes
        $totalTaxQuery = "
            SELECT COALESCE(SUM(tax_amount), 0) as total_tax
            FROM order_taxes
            WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
        ";
        $totalTax = DB::select($totalTaxQuery, [$month, $year])[0]->total_tax;

        // Total pemasukan = credit orders + pajak
        $totalPemasukan = $totalCreditOrders + $totalTax;

        return view('general_journal.index', [
            'journals' => $journals,
            'totalCredit' => $totalPemasukan,
            'filterMonth' => $month,
            'filterYear' => $year,
        ]);
    }

public function laporanKeuangan(Request $request)
{
    $range = $request->input('range', 'bulanan');
    $from = $request->input('from');
    $to = $request->input('to');

    $query = DB::table('orders');

    if ($range === 'harian') {
        // Hasil: 2025-06-12, 2025-06-13, dst.
        $query->selectRaw("DATE(order_date) as label, SUM(total) as total")
              ->whereMonth('order_date', now()->month)
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE(order_date)");
        $period = 'harian';

    } elseif ($range === 'mingguan') {
        // Hasil: Minggu ke-x
        $query->selectRaw("YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("YEAR(order_date), WEEK(order_date, 1)");
        $period = 'mingguan';

    } elseif ($range === 'bulanan') {
        // ✅ Hasil: 2025-06, 2025-05, dst.
        $query->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE_FORMAT(order_date, '%Y-%m')");
        $period = 'bulanan';

    } elseif ($range === 'custom' && $from && $to) {
        $query->selectRaw("DATE(order_date) as label, SUM(total) as total")
              ->whereBetween('order_date', [$from, $to])
              ->groupByRaw("DATE(order_date)");
        $period = 'custom';

    } else {
        // Default ke bulanan
        $query->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE_FORMAT(order_date, '%Y-%m')");
        $period = 'bulanan';
    }

    $data = $query->orderBy('label')->get();

    return view('laporan.keuangan', [
        'data' => $data,
        'range' => $range,
        'from' => $from,
        'to' => $to,
        'period' => $period,
    ]);
}

public function exportLaporan(Request $request)
{
    $format = $request->input('format');
    $range = $request->input('range', 'bulanan');
    $from = $request->input('from');
    $to = $request->input('to');

    $query = DB::table('orders');
    $period = $range;

    if ($range === 'harian') {
        $query->selectRaw("DATE(order_date) as label, SUM(total) as total")
              ->whereMonth('order_date', now()->month)
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE(order_date)")
              ->orderBy('label');

    } elseif ($range === 'mingguan') {
        $query->selectRaw("YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("YEAR(order_date), WEEK(order_date, 1)")
              ->orderBy('year')
              ->orderBy('week');

    } elseif ($range === 'bulanan') {
        $query->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE_FORMAT(order_date, '%Y-%m')")
              ->orderBy('label');

    } elseif ($range === 'custom' && $from && $to) {
        $query->selectRaw("DATE(order_date) as label, SUM(total) as total")
              ->whereBetween('order_date', [$from, $to])
              ->groupByRaw("DATE(order_date)")
              ->orderBy('label');

    } else {
        $query->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total")
              ->whereYear('order_date', now()->year)
              ->groupByRaw("DATE_FORMAT(order_date, '%Y-%m')")
              ->orderBy('label');
        $period = 'bulanan';
    }

    $data = $query->get();

    if ($format === 'pdf') {
        $pdf = PDF::loadView('laporan.keuangan_pdf', compact('data', 'range', 'from', 'to', 'period'));
        return $pdf->download('laporan-keuangan.pdf');
    } elseif ($format === 'excel') {
        return Excel::download(new \App\Exports\LaporanKeuanganExport($data, $period), 'laporan-keuangan.xlsx');
    }

    return redirect()->back()->with('error', 'Format tidak dikenali.');
}



    public function create()
    {
        return view('general_journal.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'account' => 'required|string|max:100',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'debit.min' => 'Debit must be zero or more',
            'credit.min' => 'Credit must be zero or more',
            'image.required' => 'Foto bukti wajib diupload',
            'image.image' => 'File harus berupa gambar',
        ]);

        // Validasi custom: hanya salah satu dari debit/credit yang boleh > 0
        if (
            ($request->debit > 0 && $request->credit > 0) ||
            ($request->debit == 0 && $request->credit == 0)
        ) {
            return back()->withErrors('Either debit or credit must be greater than zero, but not both.')->withInput();
        }

        // Upload image
        $imagePath = $request->file('image')->store('general_journal_images', 'public');

        //dd($imagePath);

        // Simpan data
        GeneralJournal::create([
            'date' => $request->date,
            'account' => $request->account,
            'debit' => $request->debit,
            'credit' => $request->credit,
            'description' => $request->description,
            'image' => $imagePath, // ⬅️ Ini yang penting
        ]);

        return redirect()->route('general_journal.index')->with('success', 'Journal created successfully.');
    }



    public function edit($id)
    {
        $journal = GeneralJournal::findOrFail($id);
        return view('general_journal.edit', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'account' => 'required|string|max:255',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $journal = GeneralJournal::findOrFail($id);
        $journal->update($validated);

        return redirect()->route('general_journal.index')
            ->with('success', 'Entri jurnal berhasil diperbarui.');
    }


    public function destroy(GeneralJournal $general_journal)
    {
        $general_journal->delete();

        return redirect()->route('general_journal.index')->with('success', 'Journal deleted successfully.');
    }
}
