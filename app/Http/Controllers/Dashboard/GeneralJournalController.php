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

public function index()
{
    $journals = GeneralJournal::orderBy('date', 'desc')->get();
    return view('general_journal.index', compact('journals'));
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

    public function print(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        // Ambil data jurnal sesuai bulan dan tahun
        $journals = GeneralJournal::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        // Hitung total debit dan kredit
        $totalDebit = $journals->sum('debit');
        $totalCredit = $journals->sum('credit');

        // Saldo akhir = total debit - total kredit
        $endingBalance = $totalCredit - $totalDebit;

        // Kirim ke view PDF
        $pdf = Pdf::loadView('general_journal.print_pdf', [
            'journals' => $journals,
            'year' => $year,
            'month' => $month,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'endingBalance' => $endingBalance,
        ]);

        return $pdf->stream("jurnal-{$month}-{$year}.pdf");
    }
}
