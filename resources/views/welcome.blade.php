<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMS POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .card {
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .top-right {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .btn-primary {
            font-size: 1.2rem;
            padding: 0.75rem 2rem;
        }
    </style>
</head>

<body>

    {{-- Tombol Login/Register --}}
    @if (Route::has('login'))
    <div class="top-right text-right">
        @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-outline-success">Dashboard</a>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary mr-2">Log in</a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
        @endif
        @endauth
    </div>
    @endif

    {{-- Konten Utama --}}
    <div class="card text-center">
        <h1 class="mb-4">Selamat Datang di RMS POS</h1>
        <p class="mb-4">Bikin transaksi jadi lebih mudah!</p>

    </div>

</body>

</html>