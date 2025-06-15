<table>
    <thead>
        <tr>
            <th>Periode</th>
            <th>Total Omzet</th>
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
            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>