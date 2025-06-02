<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            width: 58mm;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .dashed {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="center">
        <h3>RMS Batam</h3>
        <p>Jl. Contoh Alamat<br>Telp: 0812-3456-7890</p>
    </div>

    <div class="dashed"></div>

    <p><strong>Customer:</strong> {{ $customer->name }}</p>
    <p><strong>Date:</strong> {{ now()->format('d-m-Y H:i') }}</p>

    <div class="dashed"></div>

    @foreach ($content as $item)
    <div class="item-row">
        <span>{{ $item->name }} ({{ $item->qty }})</span>
        <span>{{ number_format($item->subtotal, 0) }}</span>
    </div>
    @endforeach

    <div class="dashed"></div>

    <div class="item-row"><strong>Subtotal</strong> <span>{{ Cart::subtotal() }}</span></div>
    <div class="item-row"><strong>PPN</strong> <span>{{ Cart::tax() }}</span></div>
    <div class="item-row"><strong>Total</strong> <span><strong>{{ Cart::total() }}</strong></span></div>

    <div class="dashed"></div>

    <div class="center">
        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>

</html>