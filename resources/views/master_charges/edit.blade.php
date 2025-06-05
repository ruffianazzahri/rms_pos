@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h4>Edit Master Charge</h4>
    <form action="{{ route('master-charges.update', $charge->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $charge->name }}" required>
        </div>

        <div class="mb-3">
            <label>Jenis</label>
            <select name="type" class="form-control" required>
                <option value="tax" {{ $charge->type == 'tax' ? 'selected' : '' }}>Pajak</option>
                <option value="discount" {{ $charge->type == 'discount' ? 'selected' : '' }}>Diskon</option>
                <option value="service" {{ $charge->type == 'service' ? 'selected' : '' }}>Service Charge</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Persentase (%)</label>
            <input type="number" name="percentage" step="0.01" class="form-control" value="{{ $charge->percentage }}"
                required>
        </div>

        <div class="mb-3">
            <label>Status Aktif</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $charge->is_active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$charge->is_active ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('master-charges.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection