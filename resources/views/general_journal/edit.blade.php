@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h3>Edit Entri Jurnal</h3>

    <form action="{{ route('general_journal.update', $journal->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" value="{{ $journal->date }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="account">Akun</label>
            <input type="text" name="account" id="account" value="{{ $journal->account }}" class="form-control"
                required>
        </div>

        <div class="form-group">
            <label for="debit">Debit</label>
            <input type="number" name="debit" id="debit" value="{{ $journal->debit }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="credit">Kredit</label>
            <input type="number" name="credit" id="credit" value="{{ $journal->credit }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" class="form-control">{{ $journal->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('general_journal.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection