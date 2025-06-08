@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Edit Customer</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('customers_vip.update', ['customers_vip' => $customer->id]) }}"
                        method="POST">

                        @csrf
                        @method('PUT')
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="name">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $customer->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Customer Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">Customer City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                    name="city" value="{{ old('city', $customer->city) }}" required>
                                @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address">Customer Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                    required>{{ old('address', $customer->address) }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="uid">UID Kartu Member</label>
                                <div class="input-group">
                                    <input type="text" id="uid_display" class="form-control"
                                        value="{{ old('uid', $customer->uid) }}" disabled>
                                    <input type="hidden" name="uid" id="uid" value="{{ old('uid', $customer->uid) }}">
                                    <button type="button" class="btn btn-warning ml-2" data-toggle="modal"
                                        data-target="#changeUidModal">
                                        Ubah Kartu Member
                                    </button>
                                </div>
                                @error('uid')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="balance">Balance (Rp):</label>
                                <input type="number" name="balance" class="form-control" min="0" step="0.01"
                                    value="{{ old('balance', $customer->balance ?? 0) }}" required>
                            </div>



                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <a class="btn bg-danger" href="{{ route('customers_vip.index') }}">Cancel</a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="changeUidModal" tabindex="-1" role="dialog"
                            aria-labelledby="changeUidModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah UID Kartu Member</h5>
                                        <button type="button" class="close"
                                            data-dismiss="modal"><span>&times;</span></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>UID Lama</label>
                                            <input type="text" class="form-control" value="{{ $customer->uid }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="uid_new">UID Baru</label>
                                            <input type="text" name="uid_new" id="uid_new" class="form-control">

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="applyNewUid()">Simpan
                                            Perubahan</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Batal</button>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@include('components.preview-img-form')
@endsection

@section('scripts')
<script>
    function confirmChangeUid() {
        if (confirm('Apakah Anda yakin ingin mengubah UID kartu member?')) {
            $('#changeUidModal').modal('show');
        }
    }
</script>
<script>
    function applyNewUid() {
        const newUid = document.getElementById('uid_new').value;
        if (newUid.trim() === '') return;

        // Set nilai UID di hidden input form utama
        document.getElementById('uid').value = newUid;

        // Tampilkan juga di field yang disabled (hanya untuk visual)
        document.getElementById('uid_display').value = newUid;

        // Tutup modal
        $('#changeUidModal').modal('hide');
    }
</script>


@endsection