<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .kop-surat h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .kop-surat p {
            margin: 2px 0;
            font-size: 14px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-surat .garis-bawah {
            border-bottom: 3px double #000;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        @page {
            margin: 120px 50px 150px 50px;
            /* top, right, bottom, left */
        }

        body {
            position: relative;
            min-height: 100%;
            margin: 0;
            padding-bottom: 200px;
            /* space for footer */
        }

        .footer {
            position: fixed;
            bottom: 40px;
            left: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo Wak Tige" style="height: 80px; margin-bottom: 10px;">

        <h1>NASI LEMAK WAK TIGE</h1>
        <p>Bengkong Sadai</p>
        <p>Kecamatan Bengkong</p>
        <p>Kota Batam</p>
        <div class="garis-bawah"></div>
    </div>
    @php
    use Carbon\Carbon;

    $judul = 'Laporan Keuangan';

    if ($period === 'harian') {
    $judul = 'Laporan Keuangan Tanggal ' . Carbon::now()->translatedFormat('d F Y');
    } elseif ($period === 'mingguan') {
    $judul = 'Laporan Keuangan Minggu Ini (' . now()->startOfWeek()->translatedFormat('d F') . ' – ' .
    now()->endOfWeek()->translatedFormat('d F Y') . ')';
    } elseif ($period === 'bulanan') {
    $judul = 'Laporan Keuangan Bulan ' . Carbon::now()->translatedFormat('F Y');
    } elseif ($period === 'custom' && $from && $to) {
    $judul = 'Laporan Keuangan ' . Carbon::parse($from)->translatedFormat('d F Y') . ' – ' .
    Carbon::parse($to)->translatedFormat('d F Y');
    }
    @endphp

    <h1 style="text-align: center">{{ $judul }}</h1>

    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Omzet</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>
                    @if($period === 'mingguan')
                    Minggu ke-{{ $row->week }} Tahun {{ $row->year }}
                    @elseif($period === 'bulanan')
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $row->label)->translatedFormat('F Y') }}
                    @else
                    {{ $row->label }}
                    @endif
                </td>
                <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: right;">Total</th>
                <th>
                    Rp {{ number_format($data->sum('total'), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>

    </table>
    <br><br>
    <div class="footer" style="margin-top: 60px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td colspan="2" style="text-align: center; border: none; padding-bottom: 20px;">
                    Batam, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center; width: 50%; border: none; vertical-align: top;">
                    Diketahui,<br><br>
                    <div style="height: 100px;"></div> {{-- Penyeimbang tinggi stempel --}}
                    <u>
                        <hr style="width: 60%; margin: auto;">
                    </u>
                    <div style="margin-top: 5px;">Manager</div>
                    <strong>Henry Lim</strong>
                </td>
                <td style="text-align: center; width: 50%; border: none; vertical-align: top;">
                    Tertanda,<br><br>
                    <div style="height: 100px;"></div>
                    <u>
                        <hr style="width: 60%; margin: auto;">
                    </u>
                    <div style="margin-top: 5px;">Kasir</div>
                </td>
            </tr>
        </table>
    </div>




    {{-- @php
    $quotes = [
    "“Omzet besar dimulai dari langkah kecil yang konsisten.” – Nasi Lemak Wak Tige",
    "“Setiap sen yang masuk, adalah hasil dari pelayanan yang tulus.” – Wak Tige",
    "“Omzet bukan sekadar angka, tapi bukti dari kepercayaan pelanggan.”",
    "“Bisnis yang jujur, omzetnya stabil. Hati senang, pelanggan pun datang.”",
    "“Rekap omzet hari ini, semangat baru esok hari.” – Dari dapur ke dompet",
    "“Kalau omzet naik, jangan lupa naikkan juga kualitas.”",
    "“Di balik setiap nasi lemak laris, ada laporan omzet yang rapih.”"
    ];
    $selectedQuote = $quotes[array_rand($quotes)];
    @endphp


    <div class="quote-box">
        {{ $selectedQuote }}
    </div> --}}


</body>

</html>