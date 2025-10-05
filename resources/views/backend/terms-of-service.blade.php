@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container mt-4">
    <h1 class="mb-3">Syarat dan Ketentuan Penggunaan POS</h1>
    <p class="text-muted">Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <hr class="my-4">

    <p>Selamat datang di POS! Dengan mengakses atau menggunakan aplikasi dan layanan POS, Anda secara tegas
        menyatakan bahwa Anda telah membaca, memahami, dan <strong>menyetujui untuk terikat oleh</strong> semua syarat
        dan ketentuan yang diuraikan di bawah ini. Syarat penggunaan ini merupakan perjanjian hukum yang
        mengikat antara Anda (pengguna) dan POS.</p>
    <p>Jika Anda tidak menyetujui salah satu dari syarat dan ketentuan ini, mohon untuk tidak menggunakan layanan RMS
        POS.</p>

    <hr class="my-4">

    <h3>1. Penggunaan Layanan</h3>
    <p>Aplikasi POS dirancang dan dimaksudkan secara eksklusif untuk <strong>keperluan bisnis yang sah</strong>
        seperti pengelolaan toko, pencatatan transaksi, dan manajemen stok bagi toko atau pemasok (supplier). Anda
        setuju untuk menggunakan layanan ini hanya untuk tujuan yang sah dan sesuai dengan semua hukum, peraturan, dan
        kebijakan yang berlaku.</p>
    <ul>
        <li><strong>Aktivitas Terlarang:</strong> Penggunaan aplikasi untuk aktivitas ilegal, penipuan, pencemaran nama
            baik, penyalahgunaan data, atau tindakan lain yang melanggar hukum atau merugikan pihak lain
            <strong>sangat dilarang</strong>. Setiap upaya untuk merusak, mengganggu, atau mendapatkan akses
            tidak sah ke sistem POS juga akan dianggap sebagai pelanggaran serius.
        </li>
        <li><strong>Tanggung Jawab Pengguna:</strong> Anda bertanggung jawab penuh atas semua aktivitas yang terjadi di
            bawah akun Anda dan atas kepatuhan terhadap semua hukum dan peraturan yang berlaku terkait dengan
            penggunaan layanan Anda.</li>
    </ul>

    <hr class="my-4">

    <h3>2. Hak Cipta, Kepemilikan, dan Kekayaan Intelektual</h3>
    <p>Seluruh konten, fitur, fungsionalitas, desain, kode sumber, logo, merek dagang, dan semua materi lain yang
        terkandung atau terkait dengan aplikasi POS adalah <strong>milik eksklusif POS</strong> atau pemberi
        lisensinya, dan dilindungi oleh undang-undang hak cipta, merek dagang, paten, rahasia dagang, dan hak
        kekayaan intelektual lainnya.</p>
    <ul>
        <li><strong>Pembatasan:</strong> Anda tidak diperkenankan untuk menyalin, mereproduksi, mendistribusikan,
            memodifikasi, membuat karya turunan dari, menampilkan secara publik, melakukan secara publik,
            menerbitkan ulang, mengunduh, menyimpan, atau mengirimkan materi apa pun dari aplikasi kami tanpa
            persetujuan tertulis sebelumnya dari POS.</li>
        <li><strong>Lisensi Terbatas:</strong> POS memberikan Anda lisensi terbatas, non-eksklusif, tidak dapat
            dipindahtangankan, dan dapat dibatalkan untuk mengakses dan menggunakan aplikasi ini semata-mata
            untuk tujuan bisnis internal Anda, sesuai dengan syarat dan ketentuan ini.</li>
    </ul>

    <hr class="my-4">

    <h3>3. Batasan Tanggung Jawab</h3>
    <p>Meskipun POS berusaha keras untuk menyediakan layanan yang stabil dan aman, kami tidak dapat menjamin bahwa
        layanan akan selalu bebas dari kesalahan atau gangguan. Dalam batas maksimum yang diizinkan oleh hukum yang
        berlaku, POS <strong>tidak bertanggung jawab atas kerugian atau kerusakan apa pun</strong> yang timbul dari
        atau sehubungan dengan:</p>
    <ul>
        <li><strong>Kehilangan Data:</strong> Kehilangan data yang diakibatkan oleh kelalaian pengguna (misalnya,
            penghapusan yang tidak disengaja, kegagalan pencadangan), kegagalan perangkat keras atau perangkat
            lunak di sisi pengguna, atau insiden keamanan yang disebabkan oleh pihak ketiga di luar kendali
            wajar POS.</li>
        <li><strong>Kesalahan Sistem Eksternal:</strong> Kegagalan atau masalah yang timbul dari sistem eksternal,
            jaringan, atau layanan pihak ketiga yang tidak dikelola secara langsung oleh POS.</li>
        <li><strong>Gangguan Layanan:</strong> Penangguhan atau penghentian layanan yang diperlukan untuk pemeliharaan,
            pembaruan, atau keadaan darurat di luar kendali kami.</li>
    </ul>
    <p>Dalam keadaan apa pun, tanggung jawab total POS kepada Anda atas semua klaim yang timbul dari atau terkait
        dengan penggunaan layanan ini tidak akan melebihi jumlah yang Anda bayarkan kepada POS untuk penggunaan
        layanan selama dua belas (12) bulan sebelum kejadian yang menyebabkan klaim tersebut.</p>

    <hr class="my-4">

    <h3>4. Perubahan pada Syarat dan Ketentuan</h3>
    <p>Kami berhak untuk mengubah, memodifikasi, atau memperbarui Syarat dan Ketentuan ini sewaktu-waktu sesuai
        kebijaksanaan kami. Setiap perubahan akan segera berlaku setelah dipublikasikan di halaman ini. Tanggal
        "Terakhir diperbarui" di bagian atas halaman akan merefleksikan revisi terbaru.</p>
    <p>Anda disarankan untuk <strong>memeriksa halaman ini secara berkala</strong> untuk mengetahui setiap perubahan.
        Dengan melanjutkan penggunaan layanan POS setelah perubahan tersebut dipublikasikan, Anda menyatakan
        bahwa Anda telah membaca, memahami, dan <strong>menyetujui seluruh ketentuan yang diperbarui</strong>.
    </p>

    <hr class="my-4">

    <p>Dengan melanjutkan penggunaan POS, Anda secara tegas menyatakan bahwa Anda telah membaca, memahami, dan
        sepenuhnya menyetujui seluruh syarat dan ketentuan yang diuraikan di atas.</p>
</div>
@endsection