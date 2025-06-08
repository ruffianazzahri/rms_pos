@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Add Customer</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('customers_vip.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- begin: Input Image -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview"
                                            src="{{ asset('assets/images/user/1.png') }}" alt="profile-pic">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- end: Input Image -->
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="name">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Customer Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">Customer City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                    name="city" value="{{ old('city') }}" required>
                                @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address">Customer Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                    required>{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Member UID (Scan Kartu)</label><br>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#uidModal">
                                    Scan Kartu Member
                                </button>
                            </div>
                            <div class="form-group">
                                <label for="balance">Balance (Rp):</label>
                                <input type="number" name="balance" class="form-control" min="0" step="0.01"
                                    value="{{ old('balance', $customer->balance ?? 0) }}" required>
                            </div>

                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                            <a class="btn bg-danger" href="{{ route('customers_vip.index') }}">Cancel</a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="uidModal" tabindex="-1" aria-labelledby="uidModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uidModalLabel">Scan Kartu Member</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Input UID di dalam modal -->
                                        <div class="form-group">
                                            <label for="uid">Member UID</label>
                                            <input type="text" class="form-control @error('uid') is-invalid @enderror"
                                                id="uid" name="uid" value="{{ old('uid') }}" required>
                                            @error('uid')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Tombol tutup dan simpan -->
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="$('#uidModal').modal('hide')">Gunakan
                                            UID</button>
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