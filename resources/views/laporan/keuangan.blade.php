@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h4>Laporan Keuangan</h4>

    <form method="GET" action="{{ route('laporan.keuangan') }}">
        <div class="form-row mb-3">
            <select name="range" class="form-control col-md-3 mr-2" onchange="this.form.submit()">
                <option value="harian" {{ $range=='harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ $range=='mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ $range=='bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="custom" {{ $range=='custom' ? 'selected' : '' }}>Custom</option>
            </select>

            @if($range == 'custom')
            <input type="date" name="from" value="{{ $from }}" class="form-control col-md-2 mr-2" required>
            <input type="date" name="to" value="{{ $to }}" class="form-control col-md-2 mr-2" required>
            <button class="btn btn-primary col-md-2">Terapkan</button>
            @endif
        </div>
    </form>

    <a href="{{ route('laporan.keuangan.export', ['format' => 'pdf', 'range' => $range, 'from' => $from, 'to' => $to]) }}"
        class="btn btn-danger mb-3">Export PDF</a>
    <a href="{{ route('laporan.keuangan.export', ['format' => 'excel', 'range' => $range, 'from' => $from, 'to' => $to]) }}"
        class="btn btn-success mb-3">Export Excel</a>

    <table class="table table-bordered">
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
</div>
@endsection