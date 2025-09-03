<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMS POS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #000;
            color: #fff;
            overflow-x: hidden;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 5% 8%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .section-indicator {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .vertical-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.8);
            margin-right: 1rem;
        }

        .divider {
            width: 1px;
            height: 2rem;
            background-color: rgba(255, 111, 60, 0.8);
            margin: 0.5rem 0;
        }

        .section-number {
            font-size: 8rem;
            font-weight: 300;
            line-height: 0.8;
            color: rgba(255, 193, 7, 0.8);
        }

        .main-content {
            max-width: 500px;
            margin-top: 2rem;
        }

        .title {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .subtitle {
            margin-bottom: 2rem;
            font-size: 1.2rem;
            font-weight: 400;
        }

        .underline {
            width: 180px;
            height: 2px;
            background: linear-gradient(90deg, #e6a272, transparent);
            margin-top: 0.5rem;
        }

        .description {
            font-size: 1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
            max-width: 450px;
        }

        .image-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 120%;
            height: 100%;
            background-image: url("/assets/images/page-img/landingpage.jpg");
            background-size: cover;
            background-position: center;
            z-index: 1;
            opacity: 0.9;
        }


        .image-container::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.2) 100%);
        }

        .auth-links {
            position: absolute;
            top: 20px;
            right: 30px;
            z-index: 10;
        }

        .auth-links a {
            color: #fff;
            border: 1px solid #fff;
            padding: 0.4rem 1rem;
            margin-left: 0.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .auth-links a:hover {
            background-color: #ffffff33;
        }

        @media (max-width: 768px) {
            .section-number {
                font-size: 5rem;
            }

            .title {
                font-size: 2.5rem;
            }

            .main-content {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 5% 5%;
            }

            .section-number {
                font-size: 4rem;
            }

            .title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    {{-- Tombol Login/Register --}}
    @if (Route::has('login'))
    <div class="auth-links">
        @auth
        {{-- kalau user punya role Admin, arahkan ke /cashier --}}
        @if(auth()->user()->hasRole('Admin'))
        <a href="{{ url('/cashier') }}">
            <i class="fas fa-cash-register"></i> Cashier
        </a>
        @else
        <a href="{{ url('/dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        @endif
        @else
        <a href="{{ route('login') }}">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
        {{-- @if (Route::has('register'))
        <a href="{{ route('register') }}">
            <i class="fas fa-user-plus"></i> Register
        </a>
        @endif --}}
        @endauth
    </div>
    @endif



    {{-- Template Layout --}}
    <div class="container">
        <div class="content">
            <div class="section-indicator">
                <div class="vertical-text text-center">
                    <span id="hari">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}</span>
                    <span class="divider"></span>
                    <span id="tanggal">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</span>
                </div>
                <h1 class="section-number" id="jam">{{ now()->format('H:i:s') }}</h1>
            </div>

            <div class="main-content">
                <img class="img-fluid" src="{{ asset('assets/images/logo2.png') }}" style="height: auto; width: 300px;"
                    alt="Login Image">
                <h2 class="title">Selamat Datang di RMS POS</h2>
                {{-- <div class="subtitle">
                    <span>Bikin transaksi jadi lebih mudah</span>
                    <div class="underline"></div>
                </div> --}}
                <p class="description">
                    RMS POS membantu UMKM, restoran, dan toko untuk mencatat penjualan, mengelola produk, dan
                    mempercepat proses transaksi harian.
                </p>
                <p class="text-muted small" style="margin-top: 10px">
                    &copy; {{ date('Y') }} RMS POS – Rezekindo Makmur Sentosa. Semua hak cipta dilindungi.
                </p>
            </div>

        </div>
        <div class="image-container"></div>
    </div>

    {{-- JS Transisi --}}
    <script>
        document.addEventListener("mousemove", (e) => {
            const imageContainer = document.querySelector(".image-container");
            const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
            imageContainer.style.transform = `translateX(${xAxis}px) translateY(${yAxis}px)`;
        });

        document.addEventListener("DOMContentLoaded", () => {
            const content = document.querySelector(".content");
            const imageContainer = document.querySelector(".image-container");

            content.style.opacity = "0";
            imageContainer.style.opacity = "0";

            content.style.transition = "opacity 1.5s ease";
            imageContainer.style.transition = "opacity 2s ease";

            setTimeout(() => {
                content.style.opacity = "1";
                imageContainer.style.opacity = "0.9";
            }, 300);
        });
    </script>
    <script>
        function updateJam() {
        const jamElem = document.getElementById('jam');
        const now = new Date();

        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');

        jamElem.textContent = `${jam}:${menit}:${detik}`;
    }

    setInterval(updateJam, 1000);
    updateJam();
    </script>
</body>

</html>