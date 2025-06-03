<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::where('product_store', '>', 0)->get();
        $customers = Customer::all()->sortBy('name');

        return view('cashier.index', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }


    public function create()
    {
        $orders = Order::all();
        return view('cashier.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'method' => 'required|in:cash,qris,debit,credit,e-wallet',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items)) {
            return response()->json(['error' => 'Keranjang kosong!'], 422);
        }

        DB::beginTransaction();
        try {
            $subTotal = array_sum(array_column($items, 'subtotal'));

            $order = Order::create([
                'customer_id'    => $request->customer_id,
                'order_date'     => Carbon::now('Asia/Jakarta'),
                'order_status'   => 'completed',
                'total_products' => array_sum(array_column($items, 'qty')),
                'sub_total'      => $subTotal,
                'vat'            => 0,
                'invoice_no'     => 'INV-' . time(),
                'total'          => $subTotal,
                'payment_status' => 'paid',
                'pay'            => $subTotal,
                'due'            => 0,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);

                // Cek apakah stok cukup
                if ($product->product_store < $item['qty']) {
                    throw new \Exception("Stok untuk produk '{$product->product_name}' tidak cukup.");
                }

                // Kurangi stok
                $product->product_store -= $item['qty'];
                $product->save();

                // Simpan detail pesanan
                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unitcost'   => $item['price'],
                    'total'      => $item['subtotal'],
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method'   => $request->method,
                'amount'   => $subTotal,
                'status'   => 'paid',
                'paid_at'  => Carbon::now('Asia/Jakarta'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'order'   => $order->load('orderDetails.product'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }


    public function show(Payment $payment)
    {
        return view('cashier.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $orders = Order::all();
        return view('cashier.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:cash,qris,debit,credit,e-wallet',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid,failed',
        ]);

        $payment->update([
            'order_id' => $request->order_id,
            'method' => $request->method,
            'amount' => $request->amount,
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]);

        return redirect()->route('cashier.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('cashier.index')->with('success', 'Payment deleted.');
    }

    public function printNota(Request $request, $id)
    {
        $order = Order::with(['orderDetails.product', 'customer'])->findOrFail($id);

        // Ambil data dari request, kalau tidak ada pakai data di database
        $pay = $request->input('pay', $order->pay); // fallback ke nilai database
        $change = $request->input('change', max(0, $pay - $order->total));
        $method = $request->input('method', $order->payment_method ?? 'Tidak diketahui');

        // Format tanggal
        $orderDate = $order->order_date instanceof Carbon
            ? $order->order_date
            : Carbon::parse($order->order_date);

        // Nota
        $nota = "";
        $nota .= "     RMS BATAM\n";
        $nota .= "------------------------------\n";
        $nota .= "Invoice  : {$order->invoice_no}\n";
        $nota .= "Tanggal  : " . $orderDate->format('d-m-Y H:i') . "\n";

        if ($order->customer) {
            $nota .= "Pelanggan: {$order->customer->name}\n";
        }

        $nota .= "------------------------------\n";

        foreach ($order->orderDetails as $item) {
            $name   = $item->product->product_name;
            $qty    = $item->quantity;
            $price  = number_format($item->unitcost, 0, ',', '.');
            $total  = number_format($item->total, 0, ',', '.');

            $displayName = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;

            $nota .= "{$displayName}\n";
            $nota .= "  {$qty} x Rp{$price} = Rp{$total}\n";
        }

        $nota .= "------------------------------\n";
        $nota .= "Total Item : {$order->total_products}\n";
        $nota .= "Total Bayar: Rp" . number_format($order->total, 0, ',', '.') . "\n";
        $nota .= "Pembayaran: {$method}\n";
        $nota .= "Dibayar   : Rp" . number_format($pay, 0, ',', '.') . "\n";

        if ($change > 0) {
            $nota .= "Kembalian : Rp" . number_format($change, 0, ',', '.') . "\n";
        }

        $nota .= "------------------------------\n";
        $nota .= "     Terima Kasih :)\n";
        $nota .= "------------------------------\n";

        return response($nota, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
