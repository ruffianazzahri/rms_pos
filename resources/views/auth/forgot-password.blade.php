@extends('auth.body.main')

@section('container')
<div class="container mt-5">
    <h3 class="mb-4">Forgot your password?</h3>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>

            @error('email')
            <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Send Password Reset Link
        </button>
    </form>
</div>
@endsection