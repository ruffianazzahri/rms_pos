@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container mt-4">
    <h1>Privacy Policy</h1>
    <p>Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <p>RMS POS menghargai privasi Anda. Halaman ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi
        informasi pribadi pengguna.</p>

    <h4>1. Informasi yang Dikumpulkan</h4>
    <p>Kami dapat mengumpulkan informasi seperti nama, nomor telepon, alamat email, dan data transaksi toko.</p>

    <h4>2. Penggunaan Informasi</h4>
    <p>Informasi digunakan untuk pengelolaan sistem kasir, keperluan akuntansi, dan pengembangan layanan RMS POS.</p>

    <h4>3. Penyimpanan & Keamanan</h4>
    <p>Data disimpan secara aman di server internal dan hanya diakses oleh pihak yang berwenang.</p>

    <h4>4. Perubahan Kebijakan</h4>
    <p>Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Perubahan akan ditampilkan di halaman ini.</p>

    <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi tim RMS POS.</p>
</div>
@endsection