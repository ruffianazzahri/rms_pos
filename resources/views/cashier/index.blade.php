@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container">
    <h2>🧾 Sistem Kasir RMS Batam</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif



    {{-- Daftar Produk --}}
    <div class="menu">
        <h4>Menu / Produk</h4>
        <div class="grid">
            <div class="mb-3">
                <input type="text" id="search-product" class="form-control" placeholder="Cari produk...">
            </div>

            @foreach($products as $product)
            <button class="add-item" data-id="{{ $product->id }}" data-name="{{ $product->product_name }}"
                data-price="{{ $product->selling_price }}">
                {{ $product->product_name }}<br>
                <small>Rp {{ number_format($product->selling_price) }}</small>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="cart">
        <h4>🛒 Keranjang</h4>
        <table class="table table-bordered" id="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <h5>Total: Rp <span id="total">0</span></h5>



        {{-- Pembayaran --}}
        <form method="POST" action="{{ route('cashier.transaksi') }}" id="form-transaksi">
            @csrf
            <div class="input-group">
                <select class="form-control" id="customer_id" name="customer_id">
                    <option selected="" disabled="">-- Select Customer --</option>
                    @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="items" id="items-input">

            <label for="method">Metode Pembayaran:</label>
            <select name="method" class="form-control" required>
                <option value="cash" selected>Cash</option>
                <option value="qris">QRIS</option>
                <option value="debit">Debit</option>
                <option value="credit">Credit</option>
                <option value="e-wallet">E-Wallet</option>
            </select>

            <div id="cash-fields" style="display:none;" class="mt-3">
                <label for="cash_received">Uang Diterima (Rp):</label>
                <input type="number" id="cash-received" class="form-control" placeholder="Masukkan nominal" min="0">

                <label class="mt-2">Kembalian:</label>
                <input type="text" id="cash-change" class="form-control" readonly>
            </div>


            <button class="btn btn-primary mt-3" type="button" id="openConfirmModal">💰 Bayar</button>

        </form>
    </div>
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memproses transaksi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="confirmSubmit" class="btn btn-primary">Ya, Proses</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Detail Transaksi -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Isi detail transaksi akan dimasukkan di sini lewat JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="closeDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
    }

    .add-item {
        padding: 1rem;
        background: #f1f1f1;
        border: 1px solid #ccc;
        cursor: pointer;
        text-align: center;
    }

    .cart {
        margin-top: 2rem;
    }
</style>

<script>
    const methodSelect = document.querySelector('select[name="method"]');
    const cashFields = document.getElementById('cash-fields');
    const cashReceived = document.getElementById('cash-received');
    const cashChange = document.getElementById('cash-change');

    function toggleCashFields() {
        if (methodSelect.value === 'cash') {
            cashFields.style.display = 'block';
        } else {
            cashFields.style.display = 'none';
            cashReceived.value = '';
            cashChange.value = '';
        }
    }

    methodSelect.addEventListener('change', toggleCashFields);

    // Jalankan saat pertama kali halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        toggleCashFields();
    });

    cashReceived.addEventListener('input', function () {
        const received = parseFloat(this.value);
        const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const change = received - total;
        cashChange.value = change >= 0 ? 'Rp ' + change.toLocaleString() : 'Rp 0';
    });

    document.getElementById('openConfirmModal').addEventListener('click', function () {
        const selectedMethod = methodSelect.value;

        if (!selectedMethod) {
            alert('Pilih metode pembayaran terlebih dahulu.');
            return;
        }

        if (selectedMethod === 'cash') {
            const received = parseFloat(cashReceived.value || 0);
            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);

            if (isNaN(received) || received < total) {
                alert('Uang tunai kurang dari total pembayaran.');
                return;
            }
        }

        $('#confirmModal').modal('show');
    });

    document.getElementById('confirmSubmit').addEventListener('click', function () {
    document.getElementById('confirmSubmit').addEventListener('click', function () {
    const form = document.getElementById('form-transaksi');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': formData.get('_token'),
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            $('#confirmModal').modal('hide');
            const order = data.order;
            let html = `
                <p><strong>Invoice:</strong> ${order.invoice_no}</p>
                <p><strong>Tanggal:</strong> ${new Date(order.order_date).toLocaleString()}</p>
                <p><strong>Total Produk:</strong> ${order.total_products}</p>
                <p><strong>Total Harga:</strong> Rp ${order.total.toLocaleString()}</p>
                <p><strong>Status Pembayaran:</strong> ${order.payment_status}</p>
            `;
            document.getElementById('detailModalBody').innerHTML = html;
            $('#detailModal').modal('show');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan.');
    });
});

// Reload halaman setelah modal detail ditutup
$('#detailModal').on('hidden.bs.modal', function () {
    location.reload();
});
    });
</script>


<script>
    const searchInput = document.getElementById('search-product');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.add-item').forEach(button => {
            const name = button.dataset.name.toLowerCase();
            if (name.includes(keyword)) {
                button.style.display = '';
            } else {
                button.style.display = 'none';
            }
        });
    });
</script>

<script>
    const cart = [];
    const cartTableBody = document.querySelector("#cart-table tbody");
    const totalDisplay = document.getElementById("total");
    const itemsInput = document.getElementById("items-input");

    document.querySelectorAll(".add-item").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price);

            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty++;
                existing.subtotal = existing.qty * price;
            } else {
                cart.push({ id, name, qty: 1, price, subtotal: price });
            }
            renderCart();
        });
    });

    function renderCart() {
        cartTableBody.innerHTML = "";
        let total = 0;
        cart.forEach((item, index) => {
            total += item.subtotal;
            cartTableBody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>Rp ${item.subtotal.toLocaleString()}</td>
                    <td><button onclick="removeItem(${index})">Hapus</button></td>
                </tr>`;
        });
        totalDisplay.innerText = total.toLocaleString();
        itemsInput.value = JSON.stringify(cart);
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }
</script>
<script>
    document.getElementById('form-transaksi').addEventListener('submit', function(e) {
    e.preventDefault(); // penting!
});
</script>
@endsection