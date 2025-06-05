@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h4>Tambah Master Charge</h4>
    <form action="{{ route('master-charges.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis</label>
            <select name="type" class="form-control" required>
                <option value="tax">Pajak</option>
                <option value="discount">Diskon</option>
                <option value="service">Service Charge</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Persentase (%)</label>
            <input type="number" name="percentage" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status Aktif</label>
            <select name="is_active" class="form-control">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('master-charges.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection