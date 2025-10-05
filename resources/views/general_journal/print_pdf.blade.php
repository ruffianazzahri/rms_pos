<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Jurnal Umum Bulan {{ $month }}/{{ $year }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 40px;
            position: relative;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop-surat img {
            width: 80px;
            height: auto;
        }

        .kop-surat h2 {
            margin: 5px 0 0 0;
            font-size: 18px;
        }

        .kop-surat p {
            margin: 2px 0;
            font-size: 12px;
        }

        hr {
            border: 1px solid #000;
            margin-top: 10px;
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

        th {
            background-color: #eee;
        }

        .text-end {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
        }

        .signature {
            width: 100%;
            margin-top: 50px;
            text-align: right;
            position: absolute;
            bottom: 60px;
        }

        .signature p {
            margin-bottom: 60px;
        }

        .footer-note {
            width: 100%;
            text-align: center;
            font-style: italic;
            font-size: 11px;
            position: absolute;
            bottom: 20px;
            left: 0;
        }
    </style>
</head>

<body>

    {{-- Kop Surat --}}
    <div class="kop-surat">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h2>Nasi Lemak Wak Tige</h2>
        <p>Bengkong Sadai, Bengkong, Kota Batam</p>
    </div>
    <hr>

    <h3 style="text-align: center;">Jurnal Umum - {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
        {{ $year }}</h3>

    {{-- Tabel Jurnal --}}
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Akun</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $journal)
            <tr>
                <td>{{ \Carbon\Carbon::parse($journal->date)->format('d-m-Y') }}</td>
                <td>{{ $journal->account }}</td>
                <td>{{ $journal->description }}</td>
                <td class="text-end">{{ number_format($journal->debit, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($journal->credit, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Ringkasan --}}
    <div class="summary">
        <p><strong>Total Debit:</strong> Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
        <p><strong>Total Kredit:</strong> Rp {{ number_format($totalCredit, 0, ',', '.') }}</p>
        <p><strong>Saldo Akhir:</strong> Rp {{ number_format($endingBalance, 0, ',', '.') }}</p>
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature">
        <p>Batam, {{ now()->format('d-m-Y') }}</p>
        <p><strong>Diketahui Oleh,</strong></p>
        <p style="margin-top: 60px;"><strong><u>______________________</u></strong></p>
    </div>

    {{-- Catatan RMS --}}
    <div class="footer-note">
        Dokumen ini dicetak menggunakan <strong>POS</strong> oleh <strong>Rezekindo Makmur Sentosa</strong>.
    </div>

</body>

</html>