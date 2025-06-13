@extends('dashboard.body.main')
<style>
    .filter-label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 10px;
        white-space: nowrap;
        align-self: center;
    }

    .dropdown-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
            <div class="alert text-white bg-success" role="alert">
                <div class="iq-alert-text">{{ session('success') }}</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            @endif
        </div>
        <div class="col-lg-4">
            @php
            $hour = \Carbon\Carbon::now()->hour;

            $quotes = [
            "Kesuksesan adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan.",
            "Jangan takut gagal, karena kegagalan adalah guru terbaik dalam hidup.",
            "Mulailah hari ini dengan semangat baru dan tujuan yang jelas.",
            "Keberhasilan terbesar datang dari usaha yang tidak pernah menyerah.",
            "Setiap langkah kecil membawa kita lebih dekat ke tujuan besar.",
            "Jangan berhenti ketika kamu lelah, berhentilah ketika kamu selesai.",
            "Kunci kesuksesan adalah fokus dan konsistensi.",
            ];

            $randomQuote = $quotes[array_rand($quotes)];

            if ($hour >= 4 && $hour < 10) { $greeting='Selamat pagi' ; } elseif ($hour>= 10 && $hour < 15) {
                    $greeting='Selamat siang' ; } elseif ($hour>= 15 && $hour < 18) { $greeting='Selamat sore' ; } else
                        { $greeting='Selamat malam' ; } $userName=auth()->user()->name;
                        @endphp

                        <div class="card card-transparent card-block card-stretch card-height border-none">
                            <div class="card-body p-0 mt-lg-2 mt-0">
                                <h3 class="mb-3">{{ $greeting }}, {{ $userName }}!</h3>
                                <p class="mb-0 mr-4"><em>{{ $randomQuote }}</em></p>
                            </div>
                        </div>

        </div>
        {{-- <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2">
                                    <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                                </div>

                                <div>
                                    <p class="mb-2">Jumlah Transaksi Tunai</p>
                                    <h4>Rp {{ number_format($total_paid, 0, ',', ',') }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-info iq-progress progress-1" data-percent="85">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2">
                                    <i class="fas fa-hourglass-half fa-2x text-danger"></i>
                                </div>

                                <div>
                                    <p class="mb-2">Total Terutang</p>
                                    <h4>Rp {{ number_format($total_due, 0, ',', ',') }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-danger iq-progress progress-1" data-percent="70">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2">
                                    <i class="fas fa-clipboard-check fa-2x text-success"></i>
                                </div>

                                <div>
                                    <p class="mb-2">Complete Orders</p>
                                    <h4>{{ count($complete_orders) }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-success iq-progress progress-1" data-percent="75">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Omzet Penjualan</h4>
                    </div>


                    <div class="card-header-toolbar d-flex align-items-center justify-content-between">
                        <div class="dropdown-wrapper">
                            <span class="filter-label">Filter data berdasarkan</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                    id="dropdownMenuButton001" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Bulan <i class="ri-arrow-down-s-line ml-1"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton001">
                                    <a class="dropdown-item" href="#" data-period="yearly">Tahun</a>
                                    <a class="dropdown-item" href="#" data-period="monthly">Bulan</a>
                                    <a class="dropdown-item" href="#" data-period="weekly">Minggu</a>
                                    <a class="dropdown-item" href="#" data-period="daily">Harian</a>
                                </div>
                            </div>
                        </div>


                        <button type="button" class="btn btn-sm btn-outline-success ml-3" data-toggle="modal"
                            data-target="#printModal">
                            <i class="ri-printer-line"></i> Print
                        </button>
                    </div>

                </div>
                <div class="card-body">
                    <canvas id="layout1-chart1" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Modal Pilihan Cetak -->
        <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printModalLabel">Pilih Periode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <a href="{{ route('omzet.print.form', ['period' => 'daily']) }}" target="_blank"
                            class="btn btn-outline-primary m-2">Harian</a>
                        <a href="{{ route('omzet.print.form', ['period' => 'weekly']) }}" target="_blank"
                            class="btn btn-outline-info m-2">Mingguan</a>
                        <a href="{{ route('omzet.print.form', ['period' => 'monthly']) }}" target="_blank"
                            class="btn btn-outline-success m-2">Bulanan</a>
                        <a href="{{ route('omzet.print.form', ['period' => 'yearly']) }}" target="_blank"
                            class="btn btn-outline-warning m-2">Tahunan</a> <!-- Tambahan -->
                    </div>

                </div>
            </div>
        </div>


        {{-- <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Revenue Vs Cost</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton002"
                                data-toggle="dropdown">
                                This Month<i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none"
                                aria-labelledby="dropdownMenuButton002">
                                <a class="dropdown-item" href="#">Yearly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="layout1-chart-2" style="min-height: 360px;"></div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-lg-8">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Top Products</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton006"
                                data-toggle="dropdown">
                                This Month<i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none"
                                aria-labelledby="dropdownMenuButton006">
                                <a class="dropdown-item" href="#">Tahun</a>
                                <a class="dropdown-item" href="#">Bulan</a>
                                <a class="dropdown-item" href="#">Minggu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled row top-product mb-0">
                        @foreach ($products as $product)
                        <li class="col-lg-3">
                            <div class="card card-block card-stretch card-height mb-0">
                                <div class="card-body">
                                    <div class="bg-warning-light rounded">
                                        <img src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}"
                                            class="style-img img-fluid m-auto p-3" alt="image">
                                    </div>
                                    <div class="style-text text-left mt-3">
                                        <h5 class="mb-1">{{ $product->product_name }}</h5>
                                        <p class="mb-0">{{ $product->product_store }} Item</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-transparent card-block card-stretch mb-4">
                <div class="card-header d-flex align-items-center justify-content-between p-0">
                    <div class="header-title">
                        <h4 class="card-title mb-0">New Products</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div><a href="#" class="btn btn-primary view-btn font-size-14">View All</a></div>
                    </div>
                </div>
            </div>
            @foreach ($new_products as $product)
            <div class="card card-block card-stretch card-height-helf">
                <div class="card-body card-item-right">
                    <div class="d-flex align-items-top">
                        <div class="bg-warning-light rounded">
                            <img src="../assets/images/product/04.png" class="style-img img-fluid m-auto" alt="image">
                        </div>
                        <div class="style-text text-left">
                            <h5 class="mb-2">{{ $product->product_name }}</h5>
                            <p class="mb-2">Stock : {{ $product->product_store }}</p>
                            <p class="mb-0">Price : Rp{{ $product->selling_price }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div> --}}
    </div>
    <!-- Page end  -->
</div>
@endsection

@section('specificpagescripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script>
    $(function() {
    const $dropdownToggle = $('#dropdownMenuButton001');
    let currentPeriod = 'monthly'; // default

    // Init canvas dan chart kosong dulu
    const ctx = document.getElementById('layout1-chart1').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Omzet',
                data: [],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            const val = context[0].label;

                            if (currentPeriod === 'daily') {
                                return moment(val).format('DD MMM YYYY');
                            }
                            else if (currentPeriod === 'monthly') {
                                // Format jadi 01 Januari 1970 (tanggal 1 + bulan + tahun)
                                return moment(val, 'YYYY-MM').startOf('month').format('DD MMMM YYYY');
                            }
                            else if (currentPeriod === 'yearly') {
                                return val;
                            }
                            else if (currentPeriod === 'weekly') {
                                // Format "Minggu X bulan ini"
                                // val sudah berbentuk "Week XX, YYYY" dari backend
                                // Kita ambil angka minggu (XX) dan ubah jadi "Minggu X bulan ini"
                                // Tapi untuk "bulan ini" perlu tahu bulan sekarang

                                const mingguMatch = val.match(/Week (\d+), (\d{4})/);
                                if (mingguMatch) {
                                    const weekNumber = parseInt(mingguMatch[1], 10);

                                    // Gunakan moment untuk bulan sekarang
                                    const bulanIni = moment().format('MMMM');

                                    return `Minggu ${weekNumber} bulan ini`;
                                }
                                return val;
                            }

                            return val;
                        },

                        label: function(context) {
                            // ambil value data
                            let value = context.parsed.y;
                            // format dengan ribuan dan prefix Rp
                            return 'Omzet: Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: { maxRotation: 45, minRotation: 45 },
                    type: 'category'
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function loadChartData(period) {
        $.ajax({
            url: '/chart/orders',
            method: 'GET',
            data: { period: period },
            success: function(response) {
                let textMap = { yearly: 'Tahun', monthly: 'Bulan', weekly: 'Minggu', daily: 'Harian' };
                $dropdownToggle.html(textMap[period] + ' <i class="ri-arrow-down-s-line ml-1"></i>');
                currentPeriod = period;

                // Extract labels & data dari response
                const labels = response.series[0].data.map(item => item.x);
                const data = response.series[0].data.map(item => item.y);

                // Update chart data
                chart.data.labels = labels;
                chart.data.datasets[0].data = data;
                chart.update();
            },
            error: function() {
                alert('Gagal mengambil data chart!');
            }
        });
    }

    // Event dropdown klik
    $('.dropdown-menu a').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const selectedPeriod = $(this).data('period');
        if (selectedPeriod && selectedPeriod !== currentPeriod) {
            loadChartData(selectedPeriod);
        }
    });

    // Load default data monthly
    loadChartData(currentPeriod);
});
</script>
@endsection