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
    <h1 style="text-align: center">Laporan Keuangan</h1>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Omzet</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->date }}</td>
                <td>Rp {{ number_format($row->omzet, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
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
                <img src="{{ public_path('images/stamp.png') }}" alt="Stempel" style="height: 100px;"><br><br>
                <u>
                    <hr style="width: 60%; margin: auto;">
                </u>
                <div style="margin-top: 5px;">Kasir</div>
            </td>
        </tr>
    </table>

    @php
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
    </div>


</body>

</html>