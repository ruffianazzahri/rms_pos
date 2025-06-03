<!DOCTYPE html>
<html>

<head>
    <title>Omzet - {{ ucfirst($period) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
    </style>
</head>

<body onload="window.print()">
    @php
    use Carbon\Carbon;

    Carbon::setLocale('id'); // Pastikan locale di-set

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
    // Misal ambil minggu dan bulan tahun dari label
    // Format label bisa "Week 23, 2025"
    $firstLabel = $data->first()->label ?? '';
    if (preg_match('/Week (\d+), (\d{4})/', $firstLabel, $matches)) {
    $weekNum = $matches[1];
    $year = $matches[2];
    // Kita ambil tanggal minggu pertama di tahun itu, lalu + (weekNum - 1) minggu
    try {
    $date = Carbon::now()->setISODate($year, $weekNum);
    return "Laporan Omzet Minggu ke-{$weekNum}, Bulan " . $date->translatedFormat('F Y');
    } catch (\Exception $e) {
    return 'Laporan Omzet Mingguan';
    }
    }
    return 'Laporan Omzet Mingguan';
    } elseif ($period === 'daily') {
    // Ambil tanggal pertama
    $firstLabel = $data->first()->label ?? '';
    try {
    $date = Carbon::parse($firstLabel);
    return 'Laporan Omzet Hari ' . $date->translatedFormat('l, d F Y');
    } catch (\Exception $e) {
    return 'Laporan Omzet Harian';
    }
    } else {
    return 'Laporan Omzet';
    }
    }

    $reportTitle = getReportTitle($period, $data);
    @endphp

    <h2>{{ $reportTitle }}</h2>

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
                    echo $date->translatedFormat('l, d F Y'); // Hari, tanggal bulan tahun
                    } catch (\Exception $e) {
                    echo $row->label;
                    }
                    @endphp
                    @else
                    {{ $row->label }}
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
</body>

</html>