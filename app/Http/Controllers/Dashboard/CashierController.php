<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderTax;
use App\Models\OrderVip;
use App\Models\OrderVipDetails;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustomerVip;
use App\Models\MasterCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::where('product_store', '>', 0)->get();

        $restaurant_tax = MasterCharge::where('name', 'Pajak Barang dan Jasa Tertentu (PBJT) 10%')
            ->value('percentage');

        $service_charge = MasterCharge::where('name', 'Service Charge')
            ->value('percentage');

        // Ambil data dari tabel customers biasa
        $regularCustomers = Customer::select('id', 'name')->get();

        $paymentMethod = PaymentMethod::select('id', 'method_name')->get();

        //dd($paymentMethod);


        // Ambil data dari customers_vip dan tambahkan label
        $vipCustomers = CustomerVip::select('id', 'name')
            ->get()
            ->map(function ($customer) {
                $customer->name .= ' - MEMBER VIP';
                return $customer;
            });

        // Gabungkan dan urutkan
        $customers = $regularCustomers->concat($vipCustomers)->sortBy('name')->values();

        return view('cashier.index', [
            'products' => $products,
            'customers' => $customers,
            'restaurant_tax' => $restaurant_tax,
            'service_charge' => $service_charge,
            'method' => $paymentMethod,
        ]);
    }


    public function create()
    {
        $orders = Order::all();
        return view('cashier.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $customerId = $request->customer_id;

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
            $totalQty = array_sum(array_column($items, 'qty'));

            // Ambil charge dari tabel master_charges
            //$serviceCharge = MasterCharge::where('type', 'service')->where('is_active', 1)->first();
            $taxCharge     = MasterCharge::where('type', 'tax')->where('is_active', 1)->first();

            // Ambil persentase, fallback ke 0 jika tidak ada
            //$servicePercent = $serviceCharge?->percentage ?? 0;
            $taxPercent     = $taxCharge?->percentage ?? 0;

            // Hitung service charge dari subtotal
            //$serviceAmount = round(($subTotal) / 100, 2);

            // Hitung pajak dari (subtotal + service charge)
            $taxBase   = $subTotal;
            $taxAmount = round(($taxBase * $taxPercent) / 100, 2);

            // Hitung grand total
            $grandTotal = $subTotal + $taxAmount;

            // Simpan ke orders
            $order = Order::create([
                'customer_id'    => $customerId,
                'order_date'     => Carbon::now('Asia/Jakarta'),
                'order_status'   => 'completed',
                'total_products' => $totalQty,
                'sub_total'      => $subTotal,
                'service_charge' => null,
                'vat'            => $taxAmount,
                'invoice_no'     => 'INV-' . time(),
                'total'          => $grandTotal,
                'payment_status' => 'paid',
                'pay'            => $grandTotal,
                'due'            => 0,
            ]);

            // === Simpan pajak ke tabel order_taxes
            if ($taxCharge) {
                OrderTax::create([
                    'order_id'    => $order->id,
                    'tax_name'    => $taxCharge->name,
                    'tax_percent' => $taxCharge->percentage,
                    'tax_amount'  => $taxAmount,
                    'created_at'  => now(),
                ]);
            }

            // === Simpan detail barang
            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->product_store < $item['qty']) {
                    throw new \Exception("Stok tidak cukup untuk {$product->product_name}.");
                }

                $product->decrement('product_store', $item['qty']);

                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unitcost'   => $item['price'],
                    'total'      => $item['subtotal'],
                ]);
            }

            // === Simpan metode pembayaran
            Payment::create([
                'order_id' => $order->id,
                'method'   => $request->method,
                'amount'   => $grandTotal,
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
            return response()->json([
                'error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
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

    // public function printNota(Request $request, $id)
    // {
    //     $order = Order::with(['orderDetails.product', 'customer'])->findOrFail($id);

    //     // Ambil data dari request, kalau tidak ada pakai data di database
    //     $pay = $request->input('pay', $order->pay); // fallback ke nilai database
    //     $change = $request->input('change', max(0, $pay - $order->total));
    //     $method = $request->input('method', $order->payment_method ?? 'Tidak diketahui');

    //     // Format tanggal
    //     $orderDate = $order->order_date instanceof Carbon
    //         ? $order->order_date
    //         : Carbon::parse($order->order_date);

    //     // Nota
    //     $nota = "";
    //     $nota .= "     RMS BATAM\n";
    //     $nota .= "------------------------------\n";
    //     $nota .= "Invoice  : {$order->invoice_no}\n";
    //     $nota .= "Tanggal  : " . $orderDate->format('d-m-Y H:i') . "\n";

    //     if ($order->customer) {
    //         $nota .= "Pelanggan: {$order->customer->name}\n";
    //     }

    //     $nota .= "------------------------------\n";

    //     foreach ($order->orderDetails as $item) {
    //         $name   = $item->product->product_name;
    //         $qty    = $item->quantity;
    //         $price  = number_format($item->unitcost, 0, ',', '.');
    //         $total  = number_format($item->total, 0, ',', '.');

    //         $displayName = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;

    //         $nota .= "{$displayName}\n";
    //         $nota .= "  {$qty} x Rp{$price} = Rp{$total}\n";
    //     }

    //     $nota .= "------------------------------\n";
    //     $nota .= "Total Item : {$order->total_products}\n";
    //     $nota .= "Total Bayar: Rp" . number_format($order->total, 0, ',', '.') . "\n";
    //     $nota .= "Pembayaran: {$method}\n";
    //     $nota .= "Dibayar   : Rp" . number_format($pay, 0, ',', '.') . "\n";

    //     if ($change > 0) {
    //         $nota .= "Kembalian : Rp" . number_format($change, 0, ',', '.') . "\n";
    //     }

    //     $nota .= "------------------------------\n";
    //     $nota .= "     Terima Kasih :)\n";
    //     $nota .= "------------------------------\n";

    //     return response($nota, 200)
    //         ->header('Content-Type', 'text/plain; charset=UTF-8');
    // }

    public function printNota(Request $request, $id)
    {
        $order = Order::with(['orderDetails.product', 'customer'])->findOrFail($id);

        $pay = $request->input('pay', $order->pay);
        $change = $request->input('change', max(0, $pay - $order->total));
        $method = $request->input('method', $order->payment_method ?? 'Tidak diketahui');

        $subtotal = $order->sub_total;

        // Ambil persentase dari master_charges
        $taxCharge = MasterCharge::where('type', 'tax')->where('is_active', 1)->first();
        $serviceCharge = MasterCharge::where('type', 'service')->where('is_active', 1)->first();

        $servicePercent = $serviceCharge->percentage ?? 0;
        $vatPercent = $taxCharge->percentage ?? 0;

        // Hitung service dari subtotal
        $service = round($subtotal * $servicePercent / 100);

        // Pajak dihitung dari subtotal
        $vatBase = $subtotal;
        $vat = round($vatBase * $vatPercent / 100);

        $grandTotal = $vatBase + $vat;
        $change = $request->input('change', max(0, $pay - $grandTotal));
        return view('nota.print', compact('order', 'pay', 'change', 'method', 'vat', 'service', 'subtotal', 'grandTotal'));
    }
}
