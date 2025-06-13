@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container mt-4">
    <h1>Terms of Use</h1>
    <p>Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <p>Dengan menggunakan RMS POS, Anda setuju untuk mematuhi syarat dan ketentuan berikut:</p>

    <h4>1. Penggunaan Layanan</h4>
    <p>Aplikasi ini ditujukan untuk keperluan bisnis toko/supplier dan dilarang digunakan untuk aktivitas ilegal.</p>

    <h4>2. Hak Cipta & Kepemilikan</h4>
    <p>Seluruh konten dan fitur aplikasi adalah milik RMS POS dan tidak boleh disalin tanpa izin.</p>

    <h4>3. Batasan Tanggung Jawab</h4>
    <p>RMS POS tidak bertanggung jawab atas kehilangan data akibat kelalaian pengguna atau kesalahan sistem eksternal.
    </p>

    <h4>4. Perubahan Syarat</h4>
    <p>Kami dapat mengubah syarat ini sewaktu-waktu. Anda disarankan memeriksa halaman ini secara berkala.</p>

    <p>Dengan melanjutkan penggunaan, Anda menyatakan telah membaca dan menyetujui seluruh ketentuan.</p>
</div>
@endsection