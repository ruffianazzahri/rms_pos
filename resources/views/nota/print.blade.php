<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Nota Pembayaran</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            width: 58mm;
            margin: 0;
            padding: 8px 10px;
            font-family: monospace, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
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

        .item {
            margin-bottom: 4px;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .total-section div {
            margin-bottom: 4px;
        }

        .spacer {
            margin-bottom: 6px;
        }

        .bottom-padding {
            padding-bottom: 10px;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="center bold">NASI LEMAK WAK TIGE</div>
    <div class="center">STRUK PEMBAYARAN</div>
    <div class="center small">Alamat: Bengkong Sadai, Kec. Bengkong, Kota Batam</div>
    <hr>

    <div class="spacer">Invoice : {{ $order->invoice_no }}</div>
    <div class="spacer">Tanggal : {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i') }}</div>

    @if ($order->customer)
    <div class="spacer">Pelanggan : {{ $order->customer->name }}</div>
    @endif

    <hr>

    @foreach ($order->orderDetails as $item)
    <div class="item">
        <div>{{ \Illuminate\Support\Str::limit($item->product->product_name, 20) }}</div>
        <div class="flex">
            <span>{{ $item->quantity }} x Rp{{ number_format($item->unitcost, 0, ',', '.') }}</span>
            <span>Rp{{ number_format($item->total, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <hr>

    <div class="total-section">
        <div class="flex"><span>Total Item:</span><span>{{ $order->total_products }}</span></div>
        <div class="flex"><span>Subtotal:</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
        <div class="flex"><span>Pajak ({{ number_format($vat / $subtotal * 100, 0) }}%):</span><span>Rp{{
                number_format($vat, 0, ',', '.') }}</span></div>
        {{-- Uncomment jika service charge ingin ditampilkan --}}
        {{-- <div class="flex"><span>Jasa Pelayanan ({{ $servicePercent }}%):</span><span>Rp{{ number_format($service,
                0, ',', '.') }}</span></div> --}}
        <div class="flex bold"><span>Total Bayar:</span><span>Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
    </div>

    <hr>

    <div class="total-section">
        <div class="flex"><span>Pembayaran:</span><span>{{ $method }}</span></div>
        <div class="flex"><span>Dibayar:</span><span>Rp{{ number_format($pay, 0, ',', '.') }}</span></div>

        @if ($method === 'membership')
        <div class="flex"><span>Sisa Saldo:</span><span>Rp{{ number_format($remainingBalance, 0, ',', '.') }}</span>
        </div>
        @else
        <div class="flex"><span>Kembalian:</span><span>Rp{{ number_format($change, 0, ',', '.') }}</span></div>
        @endif
    </div>

    <hr>

    @php
    $wakTigeMessages = [
    'Wak Tige pernah bilang, yang suka foto struk bakal kenyang batin dan lahir.',
    'Katanya sih, kalau foto struk Wak Tige, hidup makin gurih.',
    'Ini bukan struk biasa. Ini tiket menuju perut bahagia.',
    'Wak Tige cari pahlawan kuliner yang berani upload struk ini.',
    'Kalau kamu baca ini, berarti kamu ditakdirkan foto struk dan upload hari ini.',
    'Struk ini bisa jadi bukti kamu pernah makan di tempat legendaris.',
    'Upload struk ini sekarang juga. Wak Tige sedang mengamati.',
    'Kalau kamu suka makan enak, foto struk ini dulu buat kenang-kenangan.',
    'Suatu hari nanti kamu akan bilang, "Aku pernah makan di Wak Tige, ini buktinya."',
    'Orang bijak selalu foto struk dari Wak Tige. Katanya buat dokumentasi sejarah.'
    ];
    $randomMessage = $wakTigeMessages[array_rand($wakTigeMessages)];
    @endphp

    <div class="center small bottom-padding">Terima Kasih Telah Berkunjung</div>
    <div class="center small bottom-padding"><em>Catatan Wak Tige:</em><br>{{ $randomMessage }}</div>
</body>

</html>