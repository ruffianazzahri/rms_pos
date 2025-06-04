<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Nota Pembayaran</title>
    <style>
        /* Atur lebar sesuai kertas thermal 58mm */
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            width: 58mm;
            margin: 0;
            padding: 5px 10px;
            font-family: monospace, monospace;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }

        .item-name {
            word-wrap: break-word;
            max-width: 100%;
        }
    </style>
</head>

<body onload="window.print();">

    <div class="center bold">RMS BATAM</div>
    <hr>
    <div>Invoice: {{ $order->invoice_no }}</div>
    <div>Tanggal: {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i') }}</div>

    @if ($order->customer)
    <div>Pelanggan: {{ $order->customer->name }}</div>
    @endif

    <hr>

    @foreach ($order->orderDetails as $item)
    <div class="item-name">
        {{ \Illuminate\Support\Str::limit($item->product->product_name, 20) }}
    </div>
    <div>
        {{ $item->quantity }} x Rp{{ number_format($item->unitcost, 0, ',', '.') }}
        <span class="right">= Rp{{ number_format($item->total, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <hr>

    <div>Total Item: {{ $order->total_products }}</div>
    <div class="bold">Total Bayar: Rp{{ number_format($order->total, 0, ',', '.') }}</div>
    <div>Pembayaran: {{ $method }}</div>
    <div>Dibayar: Rp{{ number_format($pay, 0, ',', '.') }}</div>

    @if ($change > 0)
    <div>Kembalian: Rp{{ number_format($change, 0, ',', '.') }}</div>
    @endif

    <hr>

    <div class="center small">Terima Kasih :)</div>
</body>

</html>