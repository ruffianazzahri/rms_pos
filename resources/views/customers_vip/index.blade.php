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
            @if (session()->has('error'))
            <div class="alert text-white bg-danger" role="alert">
                <div class="iq-alert-text">{{ session('error') }}</div>
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
                    <a href="#" class="btn btn-info add-list" data-toggle="modal" data-target="#scanCardModal">
                        <i class="fa-solid fa-qrcode mr-3"></i>Scan Kartu
                    </a>

                    <a href="{{ route('customers_vip.create') }}" class="btn btn-primary add-list"><i
                            class="fa-solid fa-plus mr-3"></i>Add Customer</a>
                    <a href="{{ route('customers_vip.index') }}" class="btn btn-danger add-list"><i
                            class="fa-solid fa-trash mr-3"></i>Hapus Pencarian</a>
                </div>
            </div>
        </div>

        <!-- Modal Scan Kartu -->
        <div class="modal fade" id="scanCardModal" tabindex="-1" aria-labelledby="scanCardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="scanCardForm" method="GET" action="{{ route('customers_vip.scan') }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scanCardModalLabel">Scan Kartu Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="scanResult" style="display:none" class="mt-3 alert alert-success"></div>
                            <div id="scanError" style="display:none" class="mt-3 alert alert-danger"></div>

                            <label for="uid_input" class="form-label">Masukkan UID Kartu:</label>
                            <input type="text" class="form-control" id="uid_input" name="uid" required autofocus>
                            <small class="form-text text-muted">Masukkan UID kartu member untuk mencari data.</small>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Cari</button>
                            <a id="editBtn" href="#" class="btn btn-warning" style="display:none">Ubah</a>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
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
                            <th>Saldo</th>
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
                            <td>Rp {{ number_format($customer->balance, 0, ',', '.') }}</td>
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

@section ('scripts')
<script>
    document.getElementById('scanCardForm').addEventListener('submit', function(e) {
    e.preventDefault(); // cegah form submit biasa

    const uid = document.getElementById('uid_input').value;
    const scanResult = document.getElementById('scanResult');
    const scanError = document.getElementById('scanError');
    const editBtn = document.getElementById('editBtn');

    // Reset tampilan
    scanResult.style.display = 'none';
    scanError.style.display = 'none';
    editBtn.style.display = 'none';

    fetch(`/customers_vip/scan?uid=${uid}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                scanResult.innerHTML = `<strong>Nama:</strong> ${data.data.nama}<br><strong>Saldo:</strong> Rp ${Number(data.data.saldo).toLocaleString('id-ID')}`;

                scanResult.style.display = 'block';

                // set link ke halaman edit
                editBtn.href = `/customers_vip/${data.data.id}/edit`;
                editBtn.style.display = 'inline-block';
            } else {
                scanError.innerHTML = data.message;
                scanError.style.display = 'block';
            }
        })
        .catch(error => {
            scanError.innerHTML = 'Terjadi kesalahan saat memproses.';
            scanError.style.display = 'block';
        });
});
</script>

@endsection