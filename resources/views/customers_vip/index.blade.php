@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
            <div class="alert text-white bg-success" role="alert">
                <div class="iq-alert-text">{{ session('success') }}</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Customer VIP (Khusus kartu member)</h4>
                    <p class="mb-0">Customer yang memiliki kartu member</p>
                </div>
                <div>
                    <a href="{{ route('customers_vip.create') }}" class="btn btn-primary add-list"><i
                            class="fa-solid fa-plus mr-3"></i>Add Customer</a>
                    <a href="{{ route('customers_vip.index') }}" class="btn btn-danger add-list"><i
                            class="fa-solid fa-trash mr-3"></i>Clear Search</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('customers_vip.index') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Row:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="row">
                                <option value="10" @if(request('row')=='10' )selected="selected" @endif>10</option>
                                <option value="25" @if(request('row')=='25' )selected="selected" @endif>25</option>
                                <option value="50" @if(request('row')=='50' )selected="selected" @endif>50</option>
                                <option value="100" @if(request('row')=='100' )selected="selected" @endif>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-3 align-self-center" for="search">Search:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search"
                                    placeholder="Search customer" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary"><i
                                            class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th>UID Kartu Member</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ (($customers->currentPage() * 10) - 10) + $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->masked_phone }}</td>
                            <td>{{ $customer->masked_uid }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="View"
                                        href="{{ route('customers_vip.show', $customer->id) }}"><i
                                            class="ri-eye-line mr-0"></i>
                                    </a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Edit"
                                        href="{{ route('customers_vip.edit', $customer) }}"><i class="
                                        ri-pencil-line mr-0"></i>
                                    </a>
                                    <form action="{{ route('customers_vip.destroy', $customer->id) }}" method="POST"
                                        style="margin-bottom: 5px">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="badge bg-warning mr-2 border-none"
                                            onclick="return confirm('Are you sure you want to delete this record?')"
                                            data-toggle="tooltip" title="Delete">
                                            <i class="ri-delete-bin-line mr-0"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
    <!-- Page end  -->
</div>

@endsection