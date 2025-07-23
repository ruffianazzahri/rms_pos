@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')

<div class="container">
    <h3>Riwayat Pembayaran</h3>

    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <select name="month" class="form-control">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month==str_pad($m, 2, '0' ,
                        STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="year" class="form-control">
                    @foreach(range(date('Y') - 5, date('Y')) as $y)
                    <option value="{{ $y }}" {{ $year==$y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered" id="payment-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Metode Pembayaran</th>
                <th>Total</th>

                <th>Status Pemesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $item)
            <tr>
                <td>{{ $item->order_id }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->payment_method }}</td>
                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <td>
                    @if($item->status === 'void')
                    <span class="badge bg-danger">Void</span>
                    @else
                    <span class="badge bg-success">Selesai</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('payment-history.show', $item->order_id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Lihat
                    </a>

                    <button class="btn btn-sm btn-primary print-nota-btn" data-id="{{ $item->order_id }}"
                        data-type="{{ $item->order_type }}">
                        <i class="fas fa-print"></i> Print Nota
                    </button>

                    @if($item->status !== 'void')
                    <form method="POST"
                        action="{{ route('payment_history.void', [$item->order_type, $item->order_id]) }}"
                        style="display:inline-block" onsubmit="return confirm('Yakin ingin void?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-ban"></i> Void Payment
                        </button>
                    </form>
                    @endif
                </td>


            </tr>
            @endforeach


        </tbody>
    </table>
</div>

@endsection

@section('scripts')
@section('scripts')
<!-- DataTables (jika belum ditambahkan) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#payment-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10
        });

        // Tombol print nota dari index
        $('.print-nota-btn').on('click', function () {
            const orderId = $(this).data('id');
            const orderType = $(this).data('type') || 'regular';

            // Nilai default karena ini dari riwayat
            const pay = 0;
            const change = 0;
            const method = 'print-via-history';
            const subtotal = 0; // Tidak tersedia langsung, bisa dibiarkan 0
            const taxPercent = 10;
            const taxAmount = Math.round(subtotal * taxPercent / 100);
            const remainingBalance = 0;

            const params = new URLSearchParams({
                pay,
                change,
                method,
                tax: taxAmount,
                type: orderType,
                remaining_balance: remainingBalance
            }).toString();

            const printWindow = window.open(`/print-nota/${orderId}?${params}`, 'Print Nota', 'width=300,height=600');
        });
    });
</script>
@endsection

@endsection