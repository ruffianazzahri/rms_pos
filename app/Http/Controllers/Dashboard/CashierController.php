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

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::all();
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
                'order_date'     => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
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
                OrderDetails::create([
                    'order_id'  => $order->id,
                    'product_id' => $item['id'],
                    'quantity'  => $item['qty'],
                    'unitcost'  => $item['price'],
                    'total'     => $item['subtotal'],
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method'   => $request->method,
                'amount'   => $subTotal,
                'status'   => 'paid',
                'paid_at'  => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
            ]);

            DB::commit();

            // Return detail order sebagai response JSON
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'order'   => $order,
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
}
