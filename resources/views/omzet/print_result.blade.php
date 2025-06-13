<!DOCTYPE html>
<html>

<head>
    <title>Omzet - {{ ucfirst($period) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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


        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .quote-box {
            margin-top: 30px;
            padding: 15px;
            border-left: 5px solid #007bff;
            background-color: #f9f9f9;
            font-style: italic;
            font-size: 14px;
        }
    </style>
</head>

<body onload="setTimeout(() => { window.print(); }, 500);">

    <div class="kop-surat">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Wak Tige" style="height: 80px; margin-bottom: 10px;">

        <h1>NASI LEMAK WAK TIGE</h1>
        <p>Bengkong Sadai</p>
        <p>Kecamatan Bengkong</p>
        <p>Kota Batam</p>
        <div class="garis-bawah"></div>
    </div>


    @php
    use Carbon\Carbon;
    Carbon::setLocale('id');

    function getReportTitle($period, $data) {
    if ($period === 'monthly') {
    $firstLabel = $data->first()->label ?? Carbon::now()->format('Y-m');
    try {
    $date = Carbon::createFromFormat('Y-m', $firstLabel);
    return 'Laporan Omzet Bulanan ';
    } catch (\Exception $e) {
    return 'Laporan Omzet Bulanan';
    }
    } elseif ($period === 'weekly') {
    $firstLabel = $data->first()->label ?? '';
    if (preg_match('/Week (\d+), (\d{4})/', $firstLabel, $matches)) {
    $weekNum = $matches[1];
    $year = $matches[2];
    try {
    $date = Carbon::now()->setISODate($year, $weekNum);
    return "Laporan Omzet Minggu ke-{$weekNum}, Bulan " . $date->translatedFormat('F Y');
    } catch (\Exception $e) {
    return 'Laporan Omzet Mingguan';
    }
    }
    return 'Laporan Omzet Mingguan';
    } elseif ($period === 'daily') {
    $firstLabel = $data->first()->label ?? '';
    try {
    $date = Carbon::parse($firstLabel);
    return 'Laporan Omzet Hari ' . $date->translatedFormat('l, d F Y');
    } catch (\Exception $e) {
    return 'Laporan Omzet Harian';
    }
    } elseif ($period === 'yearly') {
    return 'Laporan Omzet Tahunan';
    } else {
    return 'Laporan Omzet';
    }
    }

    $reportTitle = getReportTitle($period, $data);
    @endphp

    <h2 style="text-align: center;">{{ $reportTitle }}</h2>


    <table>
        <thead>
            <tr>
                <th>Periode</th>
                @if ($period === 'daily')
                <th>Jam</th>
                @endif
                <th>Omzet (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>
                    @if ($period === 'weekly')
                    {{ $row->label }}
                    @elseif ($period === 'monthly')
                    @php
                    try {
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $row->label);
                    echo 'Bulan ' . $date->translatedFormat('F Y');
                    } catch (\Exception $e) {
                    echo $row->label;
                    }
                    @endphp
                    @elseif ($period === 'daily')
                    @php
                    try {
                    $date = \Carbon\Carbon::parse($row->label);
                    echo $date->translatedFormat('l, d F Y');
                    } catch (\Exception $e) {
                    echo $row->label;
                    }
                    @endphp
                    @else
                    Tahun {{ $row->label }}
                    @endif
                </td>

                @if ($period === 'daily')
                <td>
                    @php
                    try {
                    $date = \Carbon\Carbon::parse($row->label);
                    echo $date->translatedFormat('H:00');
                    } catch (\Exception $e) {
                    echo '-';
                    }
                    @endphp
                </td>
                @endif

                <td>{{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th>Total Omzet</th>
                @if ($period === 'daily')
                <th></th>
                @endif
                <th>{{ number_format($totalOmzet, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <table style="width: 100%; margin-top: 40px; border: none;">
        <tr>
            <td style="text-align: left; width: 50%; border: none;">
                <!-- Kosong -->
            </td>
            <td style="text-align: center; width: 50%; border: none;">
                Batam, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                <br>
                Tertanda,<br><br>
                <img src="{{ asset('images/stamp.png') }}" alt="Stempel" style="height: 100px;"><br><br>
                <u>
                    <hr style="width: 60%; margin: auto;">
                </u>
                <div style="margin-top: 5px;">Kasir</div>
            </td>
        </tr>
    </table>




    <div class="quote-box" id="quoteBox">
        <!-- Quote interaktif akan muncul di sini -->
    </div>

    <script>
        const quotes = [
            "“Omzet besar dimulai dari langkah kecil yang konsisten.” – Nasi Lemak Wak Tige",
            "“Setiap sen yang masuk, adalah hasil dari pelayanan yang tulus.” – Wak Tige",
            "“Omzet bukan sekadar angka, tapi bukti dari kepercayaan pelanggan.”",
            "“Bisnis yang jujur, omzetnya stabil. Hati senang, pelanggan pun datang.”",
            "“Rekap omzet hari ini, semangat baru esok hari.” – Dari dapur ke dompet",
            "“Kalau omzet naik, jangan lupa naikkan juga kualitas.”",
            "“Di balik setiap nasi lemak laris, ada laporan omzet yang rapih.”"
        ];

        const selectedQuote = quotes[Math.floor(Math.random() * quotes.length)];
        document.getElementById('quoteBox').innerText = selectedQuote;
    </script>
</body>

</html>