<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMS POS | 403 Forbidden</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #FCEF91, #FB9E3A, #E6521F, #EA2F14);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #222;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        .error-code {
            font-size: 120px;
            font-weight: 900;
            color: #EA2F14;
            text-shadow: 2px 2px 0 #FB9E3A, 4px 4px 0 #FCEF91;
        }

        .error-message {
            font-size: 20px;
            margin-bottom: 30px;
            color: #333;
        }

        .btn-home {
            background-color: #FB9E3A;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .btn-home:hover {
            background-color: #E6521F;
        }

        .btn-home i {
            margin-right: 8px;
        }

        .illustration {
            margin-bottom: 20px;
        }

        .illustration img {
            width: 180px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-message">Oops! Kamu tidak punya izin untuk mengakses halaman ini.</div>
        <a href="{{ route('dashboard') }}" class="btn-home">
            <i class="ri-home-4-line"></i> Kembali ke Beranda
        </a>
    </div>
</body>

</html>