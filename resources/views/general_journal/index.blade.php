@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h1 class="mb-4">Jurnal Umum</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('general_journal.create') }}" class="btn btn-primary mb-3">+ Tambah Entri</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Deskripsi</th>
                    <th>Bukti</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}</td>
                    <td>{{ $journal->account }}</td>
                    <td class="text-right">{{ number_format($journal->debit, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($journal->credit, 0, ',', '.') }}</td>
                    <td>{{ $journal->description }}</td>
                    <td>
                        @if($journal->image)
                        <a href="{{ asset('storage/' . $journal->image) }}" target="_blank">Lihat</a>
                        @else
                        Tidak ada
                        @endif
                    </td>
                    <td>{{ $journal->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('general_journal.edit', $journal->id) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('general_journal.destroy', $journal->id) }}" method="POST"
                            style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus entri ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada entri jurnal.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection