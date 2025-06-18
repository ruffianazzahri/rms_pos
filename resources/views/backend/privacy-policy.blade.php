@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container mt-4">
    <h1 class="mb-3">Kebijakan Privasi RMS POS</h1>
    <p class="text-muted">Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <hr class="my-4">

    <p>Di RMS POS, kami sangat menghargai dan memprioritaskan <strong>privasi Anda</strong>. Komitmen kami adalah
        melindungi
        informasi pribadi Anda yang kami kumpulkan dan proses. Halaman Kebijakan Privasi ini dirancang untuk
        memberikan
        pemahaman yang komprehensif tentang bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi
        informasi pribadi yang Anda berikan kepada kami saat menggunakan layanan RMS POS. Kami mendorong Anda
        untuk
        membaca kebijakan ini dengan saksama agar Anda sepenuhnya memahami praktik privasi kami.</p>

    <hr class="my-4">

    <h3>1. Informasi yang Kami Kumpulkan</h3>
    <p>Untuk dapat menyediakan layanan RMS POS yang efektif dan efisien, kami mungkin perlu mengumpulkan berbagai jenis
        informasi. Informasi ini dapat mencakup, namun tidak terbatas pada:</p>
    <ul>
        <li><strong>Informasi Identifikasi Pribadi:</strong> Ini termasuk data seperti <strong>nama
                lengkap</strong>Anda, <strong>nomor
                telepon</strong> yang
            dapat dihubungi, dan <strong>alamat email</strong> Anda. Informasi ini penting untuk mengelola akun Anda dan
            memfasilitasi komunikasi.</li>
        <li><strong>Data Transaksi Toko:</strong> Kami mengumpulkan informasi yang berkaitan dengan transaksi yang
            terjadi melalui
            sistem POS Anda. Data ini mencakup rincian pembelian, produk yang terjual, tanggal dan waktu
            transaksi,
            serta informasi pembayaran. Pengumpulan data ini esensial untuk fungsi utama sistem kasir dan
            keperluan
            akuntansi.</li>
    </ul>
    <p>Kami berkomitmen untuk hanya mengumpulkan informasi yang relevan dan diperlukan untuk tujuan yang disebutkan
        dalam kebijakan ini.</p>

    <hr class="my-4">

    <h3>2. Bagaimana Kami Menggunakan Informasi Anda</h3>
    <p>Informasi yang kami kumpulkan memiliki peran krusial dalam operasional dan pengembangan layanan RMS POS. Secara
        spesifik, informasi Anda akan digunakan untuk tujuan-tujuan berikut:</p>
    <ul>
        <li><strong>Pengelolaan Sistem Kasir:</strong> Data yang Anda berikan memungkinkan kami untuk mengoperasikan,
            memelihara, dan
            meningkatkan fungsi dasar sistem kasir RMS POS Anda, memastikan transaksi berjalan lancar dan
            akurat.</li>
        <li><strong>Keperluan Akuntansi dan Pelaporan Keuangan:</strong> Informasi transaksi sangat penting untuk tujuan
            akuntansi
            yang akurat, termasuk pelaporan penjualan, manajemen inventaris, dan kepatuhan terhadap peraturan
            keuangan
            yang berlaku.</li>
        <li><strong>Pengembangan dan Peningkatan Layanan RMS POS:</strong> Kami secara berkelanjutan menganalisis data
            gabungan dan
            anonim untuk mengidentifikasi tren, memahami kebutuhan pengguna, dan mengembangkan fitur-fitur baru
            atau
            meningkatkan fitur yang sudah ada dalam layanan RMS POS, memastikan kami selalu memberikan nilai
            terbaik
            kepada Anda.</li>
    </ul>
    <p>Kami tidak akan menggunakan informasi pribadi Anda untuk tujuan lain tanpa persetujuan eksplisit dari Anda.</p>

    <hr class="my-4">

    <h3>3. Penyimpanan & Keamanan Data</h3>
    <p>Keamanan informasi pribadi Anda adalah prioritas utama kami. Kami mengambil langkah-langkah yang ketat untuk
        melindungi data Anda dari akses tidak sah, pengungkapan, perubahan, atau penghancuran.</p>
    <ul>
        <li><strong>Penyimpanan Aman:</strong> Semua data Anda disimpan secara aman di <strong>server internal</strong>
            kami. Kami menerapkan
            protokol keamanan dan enkripsi standar industri untuk melindungi integritas dan kerahasiaan
            informasi Anda.
        </li>
        <li><strong>Akses Terbatas:</strong> Akses ke data pribadi Anda <strong>sangat dibatasi</strong> dan hanya
            diberikan kepada pihak yang
            berwenang dari tim RMS POS yang memiliki kebutuhan bisnis yang sah untuk mengakses informasi
            tersebut.
            Seluruh personel yang memiliki akses tunduk pada kewajiban kerahasiaan yang ketat.</li>
    </ul>
    <p>Kami terus meninjau dan memperbarui praktik keamanan kami untuk menghadapi ancaman yang terus berkembang.</p>

    <hr class="my-4">

    <h3>4. Perubahan pada Kebijakan Privasi Ini</h3>
    <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu untuk mencerminkan perubahan dalam praktik data
        kami atau karena alasan operasional, hukum, atau peraturan lainnya. Setiap perubahan akan menjadi efektif segera
        setelah kami memposting Kebijakan Privasi yang direvisi di halaman ini. <strong>Perubahan akan selalu
            ditampilkan di
            halaman ini</strong> dengan tanggal "Terakhir diperbarui" yang diperbarui. Kami menganjurkan Anda untuk
        secara berkala
        meninjau halaman ini untuk mendapatkan informasi terbaru tentang praktik privasi kami.</p>

    <hr class="my-4">

    <p>Jika Anda memiliki pertanyaan, komentar, atau kekhawatiran mengenai Kebijakan Privasi ini atau praktik data kami,
        jangan ragu untuk <strong>menghubungi tim RMS POS</strong>. Kami siap membantu Anda dan memberikan klarifikasi
        lebih lanjut.
    </p>
</div>
@endsection