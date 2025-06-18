@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h1 class="mb-4">Jurnal Umum</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-start gap-2 mb-3">
        <a href="{{ route('general_journal.create') }}" class="btn btn-primary mr-3">+ Tambah Entri</a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#printModal">
            <i class="fas fa-print"></i> Print
        </button>
    </div>


    <!-- Modal Bootstrap 4 -->
    <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('general_journal.print') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printModalLabel">Cetak Jurnal Umum</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year">Tahun</label>
                            <select name="year" class="form-control" required>
                                @php
                                $currentYear = now()->year;
                                $years = range($currentYear - 5, $currentYear + 5);
                                @endphp
                                @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year==$currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="month">Bulan</label>
                            <select name="month" class="form-control" required>
                                @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m==now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitPrint">Cetak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="jurnalTable">
            <thead style="color: #fff !important">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Saldo Akhir</th>
                    <th>Deskripsi</th>
                    <th>Bukti</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $runningBalance = 0; @endphp
                @forelse($journals->sortBy('date') as $journal)
                @php
                $runningBalance += $journal->debit - $journal->credit;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}</td>
                    <td>{{ $journal->account }}</td>
                    <td class="text-right">{{ number_format($journal->debit, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($journal->credit, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($runningBalance, 0, ',', '.') }}</td>
                    <td>{{ $journal->description }}</td>
                    <td class="text-center">
                        @if($journal->image)
                        <a href="{{ asset('storage/' . $journal->image) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        @else
                        <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $journal->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('general_journal.edit', $journal->id) }}" class="btn btn-sm btn-warning"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('general_journal.destroy', $journal->id) }}" method="POST"
                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus entri ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada entri jurnal.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Tutup modal setelah tombol cetak ditekan (dengan jeda agar PDF sempat terbuka)
        $('#submitPrint').on('click', function () {
            setTimeout(function () {
                $('#printModal').modal('hide');
            }, 500); // 0.5 detik
        });

        // Inisialisasi DataTable hanya pada tabel dengan ID tertentu (agar tidak konflik)
        $('#jurnalTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            ordering: false,
            responsive: true
        });
    });
</script>

@endsection