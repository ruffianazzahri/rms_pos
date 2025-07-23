<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class FinancialReportController extends Controller
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

// public function index(Request $request)
// {
//     $month = $request->input('month', date('m'));
//     $year = $request->input('year', date('Y'));

//     // Ambil data pendapatan dari tabel orders dan orders_vip
//     $rawQuery = "
//         (
//             SELECT
//                 NULL AS order_id,
//                 DATE(o.order_date) AS date,
//                 'Pendapatan (Orders)' AS account,
//                 0 AS debit,
//                 SUM(o.total) AS credit,
//                 'Omzet Penjualan (Termasuk PBJT 10%)' AS description,
//                 NULL AS invoice_no,
//                 NULL AS order_date,
//                 'Otomatis dari data orders' AS image
//             FROM orders o
//             WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
//             GROUP BY DATE(o.order_date)
//         )
//         UNION ALL
//         (
//             SELECT
//                 NULL AS order_id,
//                 DATE(o.order_date) AS date,
//                 'Pendapatan (Membership)' AS account,
//                 0 AS debit,
//                 SUM(o.total) AS credit,
//                 'Omzet Penjualan - MEMBERSHIP (Termasuk PBJT 10%)' AS description,
//                 NULL AS invoice_no,
//                 NULL AS order_date,
//                 'Otomatis dari data orders membership' AS image
//             FROM orders_vip o
//             WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
//             GROUP BY DATE(o.order_date)
//         )
//         ORDER BY date ASC
//         LIMIT 30;
//     ";

//     $journals = DB::select($rawQuery, [$month, $year, $month, $year]);

//     // Total credit dari orders
//     $totalOrdersQuery = "
//         SELECT COALESCE(SUM(total), 0) as total_credit_orders
//         FROM orders
//         WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
//     ";
//     $totalCreditOrders = DB::select($totalOrdersQuery, [$month, $year])[0]->total_credit_orders;

//     // Total credit dari orders_vip
//     $totalOrdersVipQuery = "
//         SELECT COALESCE(SUM(total), 0) as total_credit_orders_vip
//         FROM orders_vip
//         WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
//     ";
//     $totalCreditOrdersVip = DB::select($totalOrdersVipQuery, [$month, $year])[0]->total_credit_orders_vip;

//     // Total pajak dari order_taxes
//     $totalTaxQuery = "
//         SELECT COALESCE(SUM(tax_amount), 0) as total_tax
//         FROM order_taxes
//         WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
//     ";
//     $totalTax = DB::select($totalTaxQuery, [$month, $year])[0]->total_tax;

//     // Total pemasukan = credit orders + credit orders_vip + pajak
//     $totalPemasukan = $totalCreditOrders + $totalCreditOrdersVip;

//     return view('financial_report.index', [
//         'journals' => $journals,
//         'totalCredit' => $totalPemasukan,
//         'filterMonth' => $month,
//         'filterYear' => $year,
//     ]);
// }

public function index(Request $request)
{
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

    // Ambil data pendapatan dari tabel orders berdasarkan payment method dan orders_vip
    $rawQuery = "
        (
            SELECT
                NULL AS order_id,
                DATE(o.order_date) AS date,
                CONCAT('Pendapatan (', UPPER(p.method), ')') AS account,
                0 AS debit,
                SUM(o.total) AS credit,
                CONCAT('Omzet Penjualan ', UPPER(p.method), ' (Termasuk PBJT 10%)') AS description,
                NULL AS invoice_no,
                NULL AS order_date,
                CONCAT('Otomatis dari data orders - ', UPPER(p.method)) AS image
            FROM orders o
            INNER JOIN payments p ON o.id = p.order_id
            WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
            GROUP BY DATE(o.order_date), p.method
        )
        UNION ALL
        (
            SELECT
                NULL AS order_id,
                DATE(o.order_date) AS date,
                'Pendapatan (Membership)' AS account,
                0 AS debit,
                SUM(o.total) AS credit,
                'Omzet Penjualan - MEMBERSHIP (Termasuk PBJT 10%)' AS description,
                NULL AS invoice_no,
                NULL AS order_date,
                'Otomatis dari data orders membership' AS image
            FROM orders_vip o
            WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
            GROUP BY DATE(o.order_date)
        )
        ORDER BY date ASC, account ASC
        LIMIT 50;
    ";

    $journals = DB::select($rawQuery, [$month, $year, $month, $year]);

    // Total credit dari orders berdasarkan payment method
    $totalOrdersByMethodQuery = "
        SELECT COALESCE(SUM(o.total), 0) as total_credit_orders
        FROM orders o
        INNER JOIN payments p ON o.id = p.order_id
        WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
    ";
    $totalCreditOrders = DB::select($totalOrdersByMethodQuery, [$month, $year])[0]->total_credit_orders;

    // Total credit dari orders_vip
    $totalOrdersVipQuery = "
        SELECT COALESCE(SUM(total), 0) as total_credit_orders_vip
        FROM orders_vip
        WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
    ";
    $totalCreditOrdersVip = DB::select($totalOrdersVipQuery, [$month, $year])[0]->total_credit_orders_vip;

    // Detail breakdown berdasarkan payment method
    $paymentBreakdownQuery = "
        SELECT
            p.method,
            COALESCE(SUM(o.total), 0) as total
        FROM orders o
        INNER JOIN payments p ON o.id = p.order_id
        WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
        GROUP BY p.method
    ";
    $paymentBreakdown = DB::select($paymentBreakdownQuery, [$month, $year]);

    // Total pajak dari order_taxes
    $totalTaxQuery = "
        SELECT COALESCE(SUM(tax_amount), 0) as total_tax
        FROM order_taxes
        WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
    ";
    $totalTax = DB::select($totalTaxQuery, [$month, $year])[0]->total_tax;

    // Total pemasukan = credit orders + credit orders_vip
    $totalPemasukan = $totalCreditOrders + $totalCreditOrdersVip;

    return view('financial_report.index', [
        'journals' => $journals,
        'totalCredit' => $totalPemasukan,
        'paymentBreakdown' => $paymentBreakdown,
        'filterMonth' => $month,
        'filterYear' => $year,
    ]);
}

public function detailByProduk(Request $request)
{
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

    $rawQuery = "
    SELECT
        o.id AS order_id,
        DATE_FORMAT(o.order_date, '%d %M %Y - %H:%i') AS date,
        GROUP_CONCAT(CONCAT(p.product_name, ' (', od.quantity, ')') ORDER BY p.product_name SEPARATOR ', ') AS product,
        SUM(od.quantity) AS quantity,
        GROUP_CONCAT(DISTINCT pay.method ORDER BY pay.method SEPARATOR ', ') AS payment_method,
        SUM(od.unitcost) AS sale_subtotal,
        o.total AS sale_total,
        ROUND(SUM(od.unitcost) * (SELECT MAX(CASE WHEN type = 'service' THEN percentage END) FROM master_charges) / 100, 0) AS service_charge,
        ROUND((SUM(od.unitcost) + SUM(od.unitcost) * (SELECT MAX(CASE WHEN type = 'service' THEN percentage END) FROM master_charges) / 100) * (SELECT MAX(CASE WHEN type = 'tax' THEN percentage END) FROM master_charges) / 100, 0) AS tax,
        o.total AS total,
        'Omzet Penjualan (Termasuk PBJT 10%)' AS description,
        o.order_date AS sale_datetime,
        'regular' AS order_type
    FROM orders o
    LEFT JOIN order_details od ON od.order_id = o.id
    LEFT JOIN products p ON p.id = od.product_id
    LEFT JOIN payments pay ON pay.order_id = o.id
    WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
    GROUP BY o.id, o.order_date, o.total

    UNION ALL

    SELECT
        ov.id AS order_id,
        DATE_FORMAT(ov.order_date, '%d %M %Y - %H:%i') AS date,
        GROUP_CONCAT(CONCAT(p.product_name, ' (', ovd.quantity, ')') ORDER BY p.product_name SEPARATOR ', ') AS product,
        SUM(ovd.quantity) AS quantity,
        'MEMBERSHIP' AS payment_method,
        SUM(ovd.unitcost) AS sale_subtotal,
        ov.total AS sale_total,
        ROUND(SUM(ovd.unitcost) * (SELECT MAX(CASE WHEN type = 'service' THEN percentage END) FROM master_charges) / 100, 0) AS service_charge,
        ROUND((SUM(ovd.unitcost) + SUM(ovd.unitcost) * (SELECT MAX(CASE WHEN type = 'service' THEN percentage END) FROM master_charges) / 100) * (SELECT MAX(CASE WHEN type = 'tax' THEN percentage END) FROM master_charges) / 100, 0) AS tax,
        ov.total AS total,
        'Omzet Penjualan VIP (Membership)' AS description,
        ov.order_date AS sale_datetime,
        'vip' AS order_type
    FROM orders_vip ov
    LEFT JOIN order_vip_details ovd ON ovd.order_id = ov.id
    LEFT JOIN products p ON p.id = ovd.product_id
    WHERE MONTH(ov.order_date) = ? AND YEAR(ov.order_date) = ?
    GROUP BY ov.id, ov.order_date, ov.total

    ORDER BY sale_datetime ASC
    LIMIT 100;
    ";

    $details = collect(DB::select($rawQuery, [$month, $year, $month, $year]));

    $grandTotal = $details->sum('total');

    return response()->json([
        'data' => $details,
        'grand_total' => $grandTotal,
    ]);
}




public function laporanKeuangan(Request $request)
{
    $range = $request->input('range', 'bulanan');
    $from = $request->input('from');
    $to = $request->input('to');

    if ($range === 'harian') {
        // Hasil: 2025-06-12, 2025-06-13, dst.
        $ordersQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders
            WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
            GROUP BY DATE(order_date)
        ";

        $ordersVipQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders_vip
            WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
            GROUP BY DATE(order_date)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [
            now()->month, now()->year,
            now()->month, now()->year
        ]);
        $period = 'harian';

    } elseif ($range === 'mingguan') {
        // Hasil: Minggu ke-x
        $ordersQuery = "
            SELECT YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY YEAR(order_date), WEEK(order_date, 1)
        ";

        $ordersVipQuery = "
            SELECT YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY YEAR(order_date), WEEK(order_date, 1)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY year, week
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);
        $period = 'mingguan';

    } elseif ($range === 'bulanan') {
        // ✅ Hasil: 2025-06, 2025-05, dst.
        $ordersQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $ordersVipQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);
        $period = 'bulanan';

    } elseif ($range === 'custom' && $from && $to) {
        $ordersQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders
            WHERE order_date BETWEEN ? AND ?
            GROUP BY DATE(order_date)
        ";

        $ordersVipQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders_vip
            WHERE order_date BETWEEN ? AND ?
            GROUP BY DATE(order_date)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [$from, $to, $from, $to]);
        $period = 'custom';

    } else {
        // Default ke bulanan
        $ordersQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $ordersVipQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);
        $period = 'bulanan';
    }

    // Agregasi data untuk menggabungkan total dari kedua tabel
    $aggregatedData = collect($data)->groupBy('label')->map(function ($items, $label) {
        return (object) [
            'label' => $label,
            'total' => $items->sum('total'),
            'year' => $items->first()->year ?? null,
            'week' => $items->first()->week ?? null
        ];
    })->values();

    return view('laporan.keuangan', [
        'data' => $aggregatedData,
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
    $period = $range;

    if ($range === 'harian') {
        $ordersQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders
            WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
            GROUP BY DATE(order_date)
        ";

        $ordersVipQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders_vip
            WHERE MONTH(order_date) = ? AND YEAR(order_date) = ?
            GROUP BY DATE(order_date)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [
            now()->month, now()->year,
            now()->month, now()->year
        ]);

    } elseif ($range === 'mingguan') {
        $ordersQuery = "
            SELECT YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY YEAR(order_date), WEEK(order_date, 1)
        ";

        $ordersVipQuery = "
            SELECT YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY YEAR(order_date), WEEK(order_date, 1)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY year, week
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);

    } elseif ($range === 'bulanan') {
        $ordersQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $ordersVipQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);

    } elseif ($range === 'custom' && $from && $to) {
        $ordersQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders
            WHERE order_date BETWEEN ? AND ?
            GROUP BY DATE(order_date)
        ";

        $ordersVipQuery = "
            SELECT DATE(order_date) as label, SUM(total) as total
            FROM orders_vip
            WHERE order_date BETWEEN ? AND ?
            GROUP BY DATE(order_date)
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [$from, $to, $from, $to]);

    } else {
        $ordersQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $ordersVipQuery = "
            SELECT DATE_FORMAT(order_date, '%Y-%m') as label, SUM(total) as total
            FROM orders_vip
            WHERE YEAR(order_date) = ?
            GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ";

        $unionQuery = "
            ($ordersQuery)
            UNION ALL
            ($ordersVipQuery)
            ORDER BY label
        ";

        $data = DB::select($unionQuery, [now()->year, now()->year]);
        $period = 'bulanan';
    }

    // Agregasi data untuk menggabungkan total dari kedua tabel
    $aggregatedData = collect($data)->groupBy('label')->map(function ($items, $label) {
        return (object) [
            'label' => $label,
            'total' => $items->sum('total'),
            'year' => $items->first()->year ?? null,
            'week' => $items->first()->week ?? null
        ];
    })->values();

    if ($format === 'pdf') {
        $pdf = PDF::loadView('laporan.keuangan_pdf', [
            'data' => $aggregatedData,
            'range' => $range,
            'from' => $from,
            'to' => $to,
            'period' => $period
        ]);
        return $pdf->download('laporan-keuangan.pdf');
    } elseif ($format === 'excel') {
        return Excel::download(new \App\Exports\LaporanKeuanganExport($aggregatedData, $period), 'laporan-keuangan.xlsx');
    }

    return redirect()->back()->with('error', 'Format tidak dikenali.');
}

}
