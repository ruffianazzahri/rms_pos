<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralJournalController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $rawQuery = "
            SELECT * FROM (
                SELECT
                    gj.id, gj.date as date, gj.account, gj.debit, gj.credit, gj.description, gj.order_id,
                    NULL as invoice_no, NULL as order_date, gj.image as image
                FROM general_journal gj
                WHERE MONTH(gj.date) = ? AND YEAR(gj.date) = ?

                UNION ALL

                SELECT
                    NULL as id,
                    DATE(o.order_date) as date,
                    'Pendapatan (Orders)' as account,
                    0 as debit,
                    SUM(o.total) as credit,
                    'Omzet dari Pembelian Customer' as description,
                    NULL as order_id,
                    NULL as invoice_no,
                    NULL as order_date, 'Otomatis dari data orders' as image
                FROM orders o
                WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
                GROUP BY DATE(o.order_date)
            ) AS combined
            ORDER BY date ASC
            LIMIT 10;

            ";

        $journals = DB::select($rawQuery, [$month, $year, $month, $year]);

        // Total debit dan credit dari general_journal
        $totalGJQuery = "
        SELECT
            COALESCE(SUM(debit), 0) as total_debit,
            COALESCE(SUM(credit), 0) as total_credit
        FROM general_journal
        WHERE MONTH(date) = ? AND YEAR(date) = ?
        ";
        $totalsGJ = DB::select($totalGJQuery, [$month, $year])[0];

        // Total credit dari orders
        $totalOrdersQuery = "
        SELECT COALESCE(SUM(total), 0) as total_credit_orders
        FROM orders
        WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
        ";
        $totalCreditOrders = DB::select($totalOrdersQuery, [$month, $year])[0]->total_credit_orders;

        // Total pemasukan = general_journal credit + orders total
        $totalPemasukan = $totalsGJ->total_credit + $totalCreditOrders;

        // Total pengeluaran = general_journal debit
        $totalPengeluaran = $totalsGJ->total_debit;

        // Saldo akhir = total pemasukan - total pengeluaran
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('general_journal.index', [
            'journals' => $journals,
            'totalDebit' => $totalPengeluaran,
            'totalCredit' => $totalPemasukan,
            'saldoAkhir' => $saldoAkhir,
            'filterMonth' => $month,
            'filterYear' => $year,
        ]);
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



    public function edit(GeneralJournal $general_journal)
    {
        return view('general_journal.edit', compact('general_journal'));
    }

    public function update(Request $request, GeneralJournal $general_journal)
    {
        $request->validate([
            'date' => 'required|date',
            'account' => 'required|string|max:100',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $general_journal->update($request->all());

        return redirect()->route('general_journal.index')->with('success', 'Journal updated successfully.');
    }

    public function destroy(GeneralJournal $general_journal)
    {
        $general_journal->delete();

        return redirect()->route('general_journal.index')->with('success', 'Journal deleted successfully.');
    }
}
