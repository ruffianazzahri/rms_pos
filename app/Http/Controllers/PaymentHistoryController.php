<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PaymentHistoryController extends Controller
{
public function index(Request $request)
{
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

    $query = "
        SELECT
            o.id AS order_id,
            DATE_FORMAT(o.order_date, '%d %M %Y - %H:%i') AS date,
            GROUP_CONCAT(DISTINCT pay.method ORDER BY pay.method SEPARATOR ', ') AS payment_method,
            o.total AS total,
            o.order_status AS status,
            'regular' AS order_type
        FROM orders o
        LEFT JOIN payments pay ON pay.order_id = o.id
        WHERE MONTH(o.order_date) = ? AND YEAR(o.order_date) = ?
            AND pay.method IS NOT NULL
        GROUP BY o.id, o.order_date, o.total, o.order_status

        UNION ALL

        SELECT
            ov.id AS order_id,
            DATE_FORMAT(ov.order_date, '%d %M %Y - %H:%i') AS date,
            GROUP_CONCAT(DISTINCT pay.method ORDER BY pay.method SEPARATOR ', ') AS payment_method,
            ov.total AS total,
            ov.order_status AS status,
            'vip' AS order_type
        FROM orders_vip ov
        LEFT JOIN payments_vip pay ON pay.order_id = ov.id
        WHERE MONTH(ov.order_date) = ? AND YEAR(ov.order_date) = ?
            AND pay.method IS NOT NULL
        GROUP BY ov.id, ov.order_date, ov.total, ov.order_status

        ORDER BY date ASC
        LIMIT 100;
    ";

    $payments = collect(DB::select($query, [$month, $year, $month, $year]));

    return view('payment_history.index', [
        'payments' => $payments,
        'month' => $month,
        'year' => $year
    ]);
}



    // Tampilkan detail pembayaran
    public function show($id)
    {
        // Cek dari orders regular
        $detail = DB::select("
            SELECT
                o.id AS order_id,
                DATE_FORMAT(o.order_date, '%d %M %Y - %H:%i') AS date,
                GROUP_CONCAT(CONCAT(p.product_name, ' : ', FORMAT(od.unitcost/od.quantity, 0), ' x ', od.quantity) ORDER BY p.product_name SEPARATOR ', ') AS sale_subtotal,
                SUM(od.quantity) AS quantity,
                GROUP_CONCAT(DISTINCT pay.method ORDER BY pay.method SEPARATOR ', ') AS payment_method,
                o.total AS sale_total,
                ROUND(SUM(od.unitcost) * (SELECT MAX(CASE WHEN type = 'tax' THEN percentage END) FROM master_charges) / 100, 0) AS tax,
                o.total AS total,
                'Omzet Penjualan (Termasuk PBJT 10%)' AS description,
                'regular' AS order_type
            FROM orders o
            LEFT JOIN order_details od ON od.order_id = o.id
            LEFT JOIN products p ON p.id = od.product_id
            LEFT JOIN payments pay ON pay.order_id = o.id
            WHERE o.id = ?
            GROUP BY o.id, o.order_date, o.total
        ", [$id]);

        if (empty($detail)) {
            // Jika bukan regular, coba cari di vip
            $detail = DB::select("
                SELECT
                    ov.id AS order_id,
                    DATE_FORMAT(ov.order_date, '%d %M %Y - %H:%i') AS date,
                    GROUP_CONCAT(CONCAT(p.product_name, ' : ', FORMAT(ovd.unitcost/ovd.quantity, 0), ' x ', ovd.quantity) ORDER BY p.product_name SEPARATOR ', ') AS sale_subtotal,
                    SUM(ovd.quantity) AS quantity,
                    GROUP_CONCAT(DISTINCT pay.method ORDER BY pay.method SEPARATOR ', ') AS payment_method,
                    ov.total AS sale_total,
                    ROUND(SUM(ovd.unitcost) * (SELECT MAX(CASE WHEN type = 'tax' THEN percentage END) FROM master_charges) / 100, 0) AS tax,
                    ov.total AS total,
                    'Omzet Penjualan VIP (Termasuk PBJT 10%)' AS description,
                    'vip' AS order_type
                FROM orders_vip ov
                LEFT JOIN order_vip_details ovd ON ovd.order_id = ov.id
                LEFT JOIN products p ON p.id = ovd.product_id
                LEFT JOIN payments_vip pay ON pay.order_id = ov.id
                WHERE ov.id = ?
                GROUP BY ov.id, ov.order_date, ov.total
            ", [$id]);
        }

        if (empty($detail)) {
            return redirect()->route('payment-history.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('payment_history.show', [
            'payment' => $detail[0]
        ]);
    }

public function void(Request $request, $type, $id)
{
    $request->merge(['order_type' => $type]); // Inject manual jika perlu validasi

    $request->validate([
        'order_type' => 'required|in:regular,vip',
    ]);

    if ($type === 'regular') {
        $order = \App\Models\Order::findOrFail($id);
    } else {
        $order = \App\Models\OrderVip::findOrFail($id);
    }

    $order->order_status = 'void';
    $order->save();

    return redirect()->route('payment-history.index')->with('success', 'Transaksi berhasil dibatalkan (void).');
}

}
