@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')

<div class="container">
    <div class="container">
        <h3>Detail Pembayaran</h3>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID Order</th>
                        <td>{{ $payment->order_id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $payment->date }}</td>
                    </tr>
                    <tr>
                        <th>Produk</th>
                        <td>{!! nl2br(e($payment->sale_subtotal)) !!}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Item</th>
                        <td>{{ $payment->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ $payment->payment_method }}</td>
                    </tr>
                    <tr>
                        <th>Subtotal</th>
                        <td>Rp {{ number_format($payment->sale_total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>PPN/PBJT</th>
                        <td>Rp {{ number_format($payment->tax, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Bayar</th>
                        <td><strong>Rp {{ number_format($payment->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $payment->description }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Order</th>
                        <td>{{ strtoupper($payment->order_type) }}</td>
                    </tr>
                </table>
                <a href="{{ route('payment-history.index') }}" class="btn btn-secondary mt-3">← Kembali ke Riwayat</a>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        $(document).ready(function() {
        $('#payment-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' // Bahasa Indonesia
            },
            pageLength: 10
        });
    });
    </script>
    @endsection