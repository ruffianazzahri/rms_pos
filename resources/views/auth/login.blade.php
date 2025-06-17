@extends('auth.body.main')

@section('container')
<style>
    body {
        background: linear-gradient(135deg, #FEF3E2, #FFF);
        color: #333;
        font-family: 'Inter', sans-serif;
    }

    .auth-card {
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        overflow: hidden;
    }

    .auth-content {
        min-height: 500px;
        display: flex;
        align-items: stretch;
    }

    .floating-label {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .floating-input {
        padding-left: 2.5rem;
    }

    .floating-label i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #FA812F;
    }

    .btn-primary {
        background: #FFB22C;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1.1rem;
        transition: 0.3s;
        width: 100%;
        color: #fff;
    }

    .btn-primary:hover {
        background: #FA812F;
    }

    .content-right {
        background: #FEF3E2;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-right {
        max-width: 100%;
        height: auto;
    }

    a.text-primary {
        font-weight: 600;
        color: #F3C623 !important;
    }

    a.text-primary:hover {
        color: #FA812F !important;
    }

    h2,
    label {
        color: #FA812F;
    }

    .alert {
        font-size: 0.9rem;
    }
</style>


<div class="row align-items-center justify-content-center min-vh-100">
    <div class="col-lg-8">
        <div class="card auth-card">
            <div class="card-body p-0">
                <div class="auth-content">
                    {{-- Form Login --}}
                    <div class="col-lg-7 align-self-center p-4">
                        @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        @php
                        date_default_timezone_set('Asia/Jakarta');
                        $hour = (int) date('H');
                        if ($hour >= 4 && $hour < 11) { $salam='Selamat Pagi!' ; } elseif ($hour>= 11 && $hour < 15) {
                                $salam='Selamat Siang!' ; } elseif ($hour>= 15 && $hour < 18) { $salam='Selamat Sore!' ;
                                    } elseif ($hour>= 18 && $hour <= 21) { $salam='Selamat Malam!' ; } else {
                                        $salam='Jangan Lupa Istirahat!' ; } @endphp <h2 class="mb-3 font-weight-bold">{{
                                        $salam }}</h2>
                                        <p class="mb-4">Masuk untuk mulai menggunakan sistem POS RMS.</p>


                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="form-group floating-label">
                                                <input type="text" name="input_type"
                                                    class="floating-input form-control @error('email') is-invalid @enderror @error('username') is-invalid @enderror"
                                                    placeholder=" " value="{{ old('input_type') }}" required autofocus>
                                                <label>Email atau Username</label>
                                            </div>

                                            <div class="form-group floating-label">

                                                <input type="password" name="password"
                                                    class="floating-input form-control @error('password') is-invalid @enderror"
                                                    placeholder=" " required>
                                                <label>Password</label>
                                            </div>

                                            <button type="submit" class="btn btn-primary mt-2">Login</button>

                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="{{ route('register') }}" class="text-primary">Belum punya
                                                    akun?</a>
                                                <a href="{{ route('password.request') }}" class="text-primary">Lupa
                                                    Password?</a>
                                            </div>
                                        </form>
                    </div>

                    {{-- Gambar --}}
                    <div class="col-lg-5 content-right">
                        <img src="{{ asset('assets/images/login/01.png') }}" class="image-right" alt="Ilustrasi Login">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection