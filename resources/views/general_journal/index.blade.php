@extends('dashboard.body.main')

@section('container')
<div class="container">
    <div class="d-flex justify-content-between">
        <h3>Laporan Keuangan</h3>
        <a href="{{ route('general_journal.create') }}" class="btn btn-primary mb-3"><i
                class="fas fa-plus-circle fa-2x"></i>
            </i></a>

    </div>

    {{-- Form filter bulan & tahun --}}
    <form method="GET" action="{{ route('general_journal.index') }}" class="mb-3 row g-2 align-items-center">
        <div class="form-row align-items-end">
            <div class="form-group col-auto">
                <label for="month">Bulan</label>
                <select name="month" id="month" class="form-control">
                    @php
                    $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    @endphp
                    @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ (int)($filterMonth ?? date('m'))===$num ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group col-auto">
            <label for="year">Tahun</label>
            <select name="year" id="year" class="form-control">
                @php
                $startYear = 2020; // sesuaikan sesuai kebutuhan
                $endYear = date('Y') + 1;
                @endphp
                @for ($y = $startYear; $y <= $endYear; $y++) <option value="{{ $y }}" {{ (int)($filterYear ??
                    date('Y'))===$y ? 'selected' : '' }}>
                    {{ $y }}
                    </option>
                    @endfor
            </select>
        </div>

        <div class="form-group col-auto align-self-end">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('general_journal.index') }}" class="btn btn-link">Reset</a>
        </div>
    </form>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Akun</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Deskripsi</th>
                <th>Order ID</th>
                <th>Gambar</th> {{-- Tambah kolom image --}}
            </tr>
        </thead>
        <tbody>
            @forelse($journals as $journal)
            <tr>
                <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>

                <td>{{ $journal->account }}</td>
                <td>{{ ($journal->credit ?? 0) > 0 ? 'Rp ' . number_format($journal->credit, 2) : '-' }}</td>
                <td>{{ ($journal->debit ?? 0) > 0 ? 'Rp ' . number_format($journal->debit, 2) : '-' }}</td>
                <td>{{ $journal->description }}</td>
                <td>{{ $journal->order_id }}</td>

                <td>
                    @php

                    $image = $journal->image;
                    @endphp

                    @if(!empty($image) && Str::contains($image, '/'))
                    <button class="btn btn-sm btn-info btn-view-image" data-image-url="{{ asset('storage/' . $image) }}"
                        data-toggle="modal" data-target="#imageModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    @elseif($image === 'Otomatis dari data orders')
                    {{ $image }}
                    @else
                    -
                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No journal entries found for selected month/year.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total</th>
                <th>{{ number_format($totalCredit, 2) }}</th>
                <th>{{ number_format($totalDebit, 2) }}</th>
                <th colspan="3"></th>
            </tr>
            <tr>
                <th colspan="2" class="text-right">Saldo Akhir (Pemasukan - Pengeluaran)</th>
                <th colspan="5">{{ number_format($saldoAkhir, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- Modal untuk menampilkan gambar --}}


    {{-- Jika $journals adalah hasil DB::select, pagination manual perlu custom, tapi kalau pakai paginate() bisa
    langsung: --}}
    {{-- {{ $journals->links() }} --}}
</div>
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" alt="Bukti Foto" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Script untuk mengganti src gambar modal saat tombol diklik --}}
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-view-image');
        const modalImage = document.getElementById('modalImage');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const imageUrl = this.getAttribute('data-image-url');
                modalImage.setAttribute('src', imageUrl);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            paging: true,
            searching: true,
            ordering: true,
            order: [[0, 'asc']],
        });

        // Script modal image (tetap dari kamu)
        const buttons = document.querySelectorAll('.btn-view-image');
        const modalImage = document.getElementById('modalImage');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const imageUrl = this.getAttribute('data-image-url');
                modalImage.setAttribute('src', imageUrl);
            });
        });
    });
</script>
@endsection