@extends('dashboard.body.main')

@section('container')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <h3 class="mb-2 mb-md-0">Laporan Penjualan</h3>

        <div class="btn-group">
            <!-- Tombol Cetak -->
            <button class="btn btn-info mr-3" data-toggle="modal" data-target="#modalRange">
                <i class="fas fa-print mr-1"></i> Cetak Laporan
            </button>

            <!-- Tombol Tampilkan Rincian -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#rincianModal" id="loadDetailBtn">
                <i class="fas fa-list mr-1"></i> Tampilkan Rincian
            </button>
        </div>
    </div>



    <!-- Modal 1: Pilih Jangka Waktu -->
    <div class="modal fade" id="modalRange" tabindex="-1" role="dialog" aria-labelledby="modalRangeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-range" onsubmit="event.preventDefault(); openModalType();">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Jangka Waktu</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="range" id="harian" value="harian">
                            <label class="form-check-label" for="harian">Harian</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="range" id="mingguan" value="mingguan">
                            <label class="form-check-label" for="mingguan">Mingguan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="range" id="bulanan" value="bulanan">
                            <label class="form-check-label" for="bulanan">Bulanan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="range" id="custom" value="custom">
                            <label class="form-check-label" for="custom">Custom Tanggal</label>
                        </div>

                        <div id="custom-date-range" class="mt-3" style="display: none;">
                            <label>Dari Tanggal:</label>
                            <input type="date" class="form-control mb-2" id="from-date">
                            <label>Sampai Tanggal:</label>
                            <input type="date" class="form-control" id="to-date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lanjut</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Pilih Format -->
    <div class="modal fade" id="modalType" tabindex="-1" role="dialog" aria-labelledby="modalTypeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-export" method="GET" action="{{ route('laporan.keuangan.export') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Format Laporan</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="range" id="input-range">
                        <input type="hidden" name="from" id="input-from">
                        <input type="hidden" name="to" id="input-to">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="format-pdf" value="pdf"
                                required>
                            <label class="form-check-label" for="format-pdf">PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="format-excel" value="excel">
                            <label class="form-check-label" for="format-excel">Excel</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cetak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal 3 --}}
    <div class="modal fade" id="rincianModal" tabindex="-1" role="dialog" aria-labelledby="rincianModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Penjualan Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm" id="rincianTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Metode Pembayaran</th>
                                <th>Harga Satuan</th>
                                {{-- <th>Service</th> --}}
                                <th>Pajak</th>
                                <th>Total</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- end of cetak --}}
    {{-- Form filter bulan & tahun --}}
    <form method="GET" action="{{ route('financial_report.index') }}" class="mb-3 row g-2 align-items-center">
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
            <a href="{{ route('financial_report.index') }}" class="btn btn-link">Reset</a>
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
                {{-- <th>Pengeluaran</th> --}}
                <th>Deskripsi</th>
                {{-- <th>Order ID</th> --}}
                <th>Lainnya</th> {{-- Tambah kolom image --}}
                {{-- <th>Aksi</th> --}}

            </tr>
        </thead>
        <tbody>
            @forelse($journals as $journal)
            <tr>
                <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>

                <td>{{ $journal->account }}</td>
                <td>{{ ($journal->credit ?? 0) > 0 ? 'Rp ' . number_format($journal->credit, 2) : '-' }}</td>
                {{-- <td>{{ ($journal->debit ?? 0) > 0 ? 'Rp ' . number_format($journal->debit, 2) : '-' }}</td> --}}
                <td>{{ $journal->description }}</td>
                {{-- <td>{{ $journal->order_id }}</td> --}}

                <td>
                    @php
                    $image = $journal->image;
                    @endphp

                    @if(!empty($image) && Str::contains($image, '/'))
                    <button class="btn btn-sm btn-info btn-view-image" data-image-url="{{ asset('storage/' . $image) }}"
                        data-toggle="modal" data-target="#imageModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    @elseif(Str::contains($image, 'Otomatis dari data orders'))
                    {{ $image }}
                    @else
                    -
                    @endif
                </td>
                {{-- <td>
                    @if($journal->id)
                    <a href="{{ route('financial_report.edit', $journal->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td> --}}


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
                {{-- <th>{{ number_format($totalDebit, 2) }}</th> --}}
                <th colspan="2"></th>
            </tr>
            {{-- <tr>
                <th colspan="2" class="text-right">Saldo Akhir (Pemasukan - Pengeluaran)</th>
                <th colspan="6">{{ number_format($saldoAkhir, 2) }}</th>
            </tr> --}}
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
        $.fn.dataTable.ext.errMode = 'none';

        $('.table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            paging: true,
            searching: true,
            ordering: true,
            order: [[0, 'asc']],

            // Optional tambahan
            responsive: true,           // Responsif
            autoWidth: false,           // Hindari lebar kolom otomatis yang aneh
            columnDefs: [
                {
                    targets: '_all',    // Terapkan ke semua kolom
                    defaultContent: '', // Jika ada kolom kosong, jangan error
                }
            ]
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
<script>
    $(document).ready(function () {
    // Tampilkan input tanggal jika pilih "custom"
    $('input[name="range"]').change(function () {
        if (this.value === 'custom') {
            $('#custom-date-range').slideDown();
        } else {
            $('#custom-date-range').slideUp();
        }
    });

    // Modal 1 submit → buka Modal 2
    window.openModalType = function () {
        const range = $('input[name="range"]:checked').val();

        if (!range) {
            alert('Silakan pilih jangka waktu laporan.');
            return;
        }

        if (range === 'custom') {
            const from = $('#from-date').val();
            const to = $('#to-date').val();
            if (!from || !to) {
                alert('Tanggal From dan To harus diisi untuk custom range.');
                return;
            }
            $('#input-from').val(from);
            $('#input-to').val(to);
        } else {
            $('#input-from').val('');
            $('#input-to').val('');
        }

        $('#input-range').val(range);
        $('#modalRange').modal('hide');
        $('#modalType').modal('show');
    }

    // Reset form saat modal ditutup
    $('#modalRange, #modalType').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#custom-date-range').hide();
    });
});
</script>
<script>
    $(document).ready(function () {
    $('#loadDetailBtn').click(function () {
        $.ajax({
            url: '/products-sale',
            method: 'GET',
            data: {
                month: '{{ $filterMonth }}',
                year: '{{ $filterYear }}'
            },
            success: function (response) {
                const tbody = $('#rincianTable tbody');
                tbody.empty();

                // Loop data produk
                response.data.forEach(function (item, index) {
                    const row = `
                        <tr>
                            <td>${item.order_id ?? '-'}</td>
                            <td>${item.date ?? '-'}</td>
                            <td>${item.product}</td>
                            <td>${item.quantity}</td>
                            <td>${item.payment_method}</td>
                            <td>${item.sale_subtotal}</td>
                            <td>Rp ${parseInt(item.tax).toLocaleString('id-ID')}</td>
                            <td>Rp ${parseInt(item.total).toLocaleString('id-ID')}</td>
                            <td>${item.description}</td>
                        </tr>`;
                    tbody.append(row);
                });

                // Tambah baris total keseluruhan
                const totalRow = `
                    <tr class="font-weight-bold bg-light">
                        <td colspan="7" class="text-right">Total</td>
                        <td colspan="2">Rp ${parseInt(response.grand_total).toLocaleString('id-ID')}</td>
                    </tr>`;
                tbody.append(totalRow);

                // Tampilkan modal
                $('#rincianModal').modal('show');
            },
            error: function () {
                alert("Gagal mengambil data!");
            }
        });
    });
});
</script>

@endsection