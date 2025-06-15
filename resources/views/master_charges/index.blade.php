@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h4>Master Pajak / Diskon / Charge</h4>
    <a href="{{ route('master-charges.create') }}" class="btn btn-primary mb-3">Tambah Data</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Persentase (%)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($charges as $charge)
            <tr>
                <td>{{ $charge->name }}</td>
                <td>{{ ucfirst($charge->type) }}</td>
                <td>{{ $charge->percentage }}</td>
                <td>{{ $charge->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>
                    @if($charge->type === 'tax')
                    <span class="text-muted small">Hubungi Superadmin atau Developer aplikasi untuk mengubah persentase
                        pajak.</span>
                    @else
                    <a href="{{ route('master-charges.edit', $charge->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('master-charges.destroy', $charge->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')"
                            class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection