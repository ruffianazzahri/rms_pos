@extends('auth.body.main')

@section('container')
<div class="container">
    <h2>Reset Password</h2>
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('password.updatepassword') }}">
        @csrf
        @method('PUT') {{-- ini wajib karena route update pakai PUT --}}

        <input name="token" type="hidden" value="{{ request()->route('token') }}">

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input id="email" type="email" class="form-control" name="email" required autofocus
                value="{{ old('email') }}">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mt-2">
            <label for="password">Password Baru</label>
            <input id="password" type="password" class="form-control" name="password" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mt-2">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">
            Simpan Password Baru
        </button>
    </form>
</div>
@endsection