<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pendingOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'pending')->sortable()->paginate($row);

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }

    public function completeOrders()
    {
        $row = (int) request('row', 10);
        $search = request('search');

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'completed')
            ->when($search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('invoice_no', 'like', "%{$search}%");
            })
            ->orderBy('order_date', 'desc')
            ->sortable()
            ->paginate($row);

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }

    public function printNota($id)
    {
        $order = Order::with(['orderDetails.product', 'customer'])->findOrFail($id);

        $nota = "";
        $nota .= "RMS BATAM\n";
        $nota .= "--------------------------\n";
        $nota .= "Invoice: " . $order->invoice_no . "\n";
        $nota .= "Tanggal: " . $order->order_date->format('d-m-Y H:i') . "\n";
        if ($order->customer) {
            $nota .= "Pelanggan: " . $order->customer->name . "\n";
        }
        $nota .= "--------------------------\n";

        foreach ($order->orderDetails as $item) {
            $name = $item->product->product_name;
            $qty = $item->quantity;
            $price = number_format($item->unitcost);
            $total = number_format($item->total);
            $nota .= "{$name}\n  {$qty} x Rp{$price} = Rp{$total}\n";
        }

        $nota .= "--------------------------\n";
        $nota .= "Total Produk : {$order->total_products}\n";
        $nota .= "Total Harga  : Rp" . number_format($order->total) . "\n";
        $nota .= "Bayar        : Rp" . number_format($order->pay) . "\n";
        $nota .= "Kembalian    : Rp" . number_format($order->due < 0 ? abs($order->due) : 0) . "\n";
        $nota .= "--------------------------\n";
        $nota .= "Terima Kasih\n";

        return Response::make($nota, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }


    public function stockManage()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('stock.index', [
            'products' => Product::with(['category', 'supplier'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrder(Request $request)
    {
        $rules = [
            'customer_id' => 'required|numeric',
            'payment_status' => 'required|string',
            'pay' => 'numeric|nullable',
            'due' => 'numeric|nullable',
        ];

        $invoice_no = IdGenerator::generate([
            'table' => 'orders',
            'field' => 'invoice_no',
            'length' => 10,
            'prefix' => 'INV-'
        ]);

        $validatedData = $request->validate($rules);
        $validatedData['order_date'] = Carbon::now()->format('Y-m-d');
        $validatedData['order_status'] = 'pending';
        $validatedData['total_products'] = Cart::count();
        $validatedData['sub_total'] = Cart::subtotal();
        $validatedData['vat'] = Cart::tax();
        $validatedData['invoice_no'] = $invoice_no;
        $validatedData['total'] = Cart::total();
        $validatedData['due'] = Cart::total() - $validatedData['pay'];
        $validatedData['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($validatedData);

        // Create Order Details
        $contents = Cart::content();
        $oDetails = array();

        foreach ($contents as $content) {
            $oDetails['order_id'] = $order_id;
            $oDetails['product_id'] = $content->id;
            $oDetails['quantity'] = $content->qty;
            $oDetails['unitcost'] = $content->price;
            $oDetails['total'] = $content->total;
            $oDetails['created_at'] = Carbon::now();

            OrderDetails::insert($oDetails);
        }

        // Delete Cart Sopping History
        Cart::destroy();

        return Redirect::route('dashboard')->with('success', 'Order has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function orderDetails(Int $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
            ->where('order_id', $order_id)
            ->orderBy('id', 'DESC')
            ->get();

        return view('orders.details-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        $order_id = $request->id;

        // Reduce the stock
        $products = OrderDetails::where('order_id', $order_id)->get();

        foreach ($products as $product) {
            Product::where('id', $product->product_id)
                ->update(['product_store' => DB::raw('product_store-' . $product->quantity)]);
        }

        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        return Redirect::route('order.pendingOrders')->with('success', 'Order has been completed!');
    }

    public function invoiceDownload(Int $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
            ->where('order_id', $order_id)
            ->orderBy('id', 'DESC')
            ->get();

        // show data (only for debugging)
        return view('orders.invoice-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    public function pendingDue()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('due', '>', '0')
            ->sortable()
            ->paginate($row);

        return view('orders.pending-due', [
            'orders' => $orders
        ]);
    }

    public function orderDueAjax(Int $id)
    {
        $order = Order::findOrFail($id);

        return response()->json($order);
    }

    public function updateDue(Request $request)
    {
        $rules = [
            'order_id' => 'required|numeric',
            'due' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        $order = Order::findOrFail($request->order_id);
        $mainPay = $order->pay;
        $mainDue = $order->due;

        $paid_due = $mainDue - $validatedData['due'];
        $paid_pay = $mainPay + $validatedData['due'];

        Order::findOrFail($request->order_id)->update([
            'due' => $paid_due,
            'pay' => $paid_pay,
        ]);

        return Redirect::route('order.pendingDue')->with('success', 'Due Amount Updated Successfully!');
    }

    //ORDER KHUSUS KAFE
    public function storeOrderKafe(Request $request)
    {
        $validatedData = $request->validate([
            'pay' => 'required|numeric', // bayar tunai wajib
        ]);

        $invoice_no = IdGenerator::generate([
            'table' => 'orders',
            'field' => 'invoice_no',
            'length' => 10,
            'prefix' => 'INV-KAFE-'
        ]);

        $total = Cart::total();

        $orderData = [
            'customer_id' => null, // bisa null, karena kafe biasanya tidak pakai customer tetap
            'payment_status' => 'cash',
            'pay' => $validatedData['pay'],
            'due' => 0,
            'order_date' => Carbon::now()->format('Y-m-d'),
            'order_status' => 'complete',
            'total_products' => Cart::count(),
            'sub_total' => Cart::subtotal(),
            'vat' => Cart::tax(),
            'total' => $total,
            'invoice_no' => $invoice_no,
            'created_at' => Carbon::now(),
        ];

        $order_id = Order::insertGetId($orderData);

        // Insert order details
        $contents = Cart::content();

        foreach ($contents as $content) {
            OrderDetails::insert([
                'order_id' => $order_id,
                'product_id' => $content->id,
                'quantity' => $content->qty,
                'unitcost' => $content->price,
                'total' => $content->total,
                'created_at' => Carbon::now(),
            ]);

            // Langsung kurangi stok
            Product::where('id', $content->id)
                ->update(['product_store' => DB::raw('product_store - ' . $content->qty)]);
        }

        Cart::destroy();

        return Redirect::route('pos.kafe.index')->with('success', 'Order Kafe berhasil dan sudah dibayar!');
    }

    public function printInvoiceKafe($order_id)
    {
        $order = Order::with('orderDetails.product', 'customer')->findOrFail($order_id);

        return view('pos.kafe-invoice', compact('order'));
    }
}
