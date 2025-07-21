@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<style>
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 1rem;
    }

    .product-button {
        background-color: #FFB22C !important;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .product-button:hover {
        background-color: #ffd48a !important;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin-bottom: 8px;
        border-radius: 10px;
    }

    .product-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .product-price {
        font-size: 0.85rem;
        color: #555;
    }

    .cart-box {
        background-color: #fff8e1;
        border-radius: 12px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
    }

    #cart-table thead {
        background: linear-gradient(to right, #FB9E3A, #E6521F);
        color: white;
        font-weight: bold;
    }

    #cart-table td,
    #cart-table th {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        font-size: 0.9rem;
    }

    #cart-table tbody tr:hover {
        background-color: #fff3cd;
    }

    .btn-sm {
        padding: 5px 8px;
        font-size: 0.8rem;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
        overflow: hidden;
    }

    .product-card:hover {
        background-color: #fff3e0;
        /* Oranye muda */
        box-shadow: 0 6px 12px rgba(255, 165, 0, 0.3);
        transform: translateY(-4px);
    }

    .product-card img {
        height: 130px;
        object-fit: cover;
        width: 100%;
        border-radius: 0.75rem 0.75rem 0 0;
    }

    .product-card .product-name {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .product-card .product-price {
        font-weight: 700;
        color: #fd7e14;
        /* Oranye khas Bootstrap */
    }

    .product-card:hover .product-price {
        color: #e8590c;
        /* Oranye lebih gelap saat hover */
    }

    .add-item {
        text-align: left;
        padding: 0;
    }

    .add-item:focus {
        outline: none;
        box-shadow: none;
    }

    .product-list-scrollable {
        max-height: 500px;
        /* Sesuaikan sesuai layout */
        overflow-y: auto;
        padding-right: 4px;
        /* untuk menghindari overlap scrollbar */
    }

    /* Optional scrollbar styling (for WebKit browsers like Chrome) */
    .product-list-scrollable::-webkit-scrollbar {
        width: 6px;
    }

    .product-list-scrollable::-webkit-scrollbar-thumb {
        background-color: #fd7e14;
        border-radius: 3px;
    }

    .product-list-scrollable::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>
<div class="container-fluid">
    <h2 class="mt-3">🧾 Sistem Kasir RMS Batam</h2>
    <p class="fw-bold">
        Jika salah satu produk tidak muncul, kemungkinan stok habis. Harap cek stok berkala
        <a href="{{ route('products.index') }}" target="_blank">disini.</a>
    </p>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mt-4">
        {{-- === MENU/PRODUK DI KIRI === --}}
        <div class="col-md-7">
            <div class="menu">
                <h4 class="mb-3">📦 Menu / Produk</h4>

                <input type="text" id="search-product" class="form-control mb-3" placeholder="Cari produk...">

                <div class="product-list-scrollable">
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <button class="product-card add-item w-100 border-0" data-id="{{ $product->id }}"
                                data-name="{{ $product->product_name }}" data-price="{{ $product->selling_price }}"
                                style="cursor: pointer;">

                                <img src="{{ $product->product_image ? asset('storage/products/' . $product->product_image) : asset('assets/images/product/default.webp') }}"
                                    alt="{{ $product->product_name }}">

                                <div class="p-2">
                                    <div class="product-name mb-1">{{ $product->product_name }}</div>
                                    <div class="product-price">Rp {{ number_format($product->selling_price) }}</div>
                                </div>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>



        {{-- === KERANJANG DAN FORM DI KANAN === --}}
        <div class="col-md-5">
            <div class="cart-box p-3">
                <h4>🛒 Keranjang</h4>

                <table class="table table-bordered table-sm mt-3" id="cart-table">
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

                <input type="hidden" id="tax-percentage" value="{{ $restaurant_tax }}">
                <input type="hidden" id="service-percentage" value="{{ $service_charge }}">

                <h5 class="mt-3">Subtotal: Rp <span id="total">0</span></h5>
                <h5 class="d-none">Jasa Pelayanan (10%): Rp <span id="service-amount">0</span></h5>
                <h5>Pajak Restoran (10%): Rp <span id="tax-amount">0</span></h5>
                <h5>Total Bayar: Rp <span id="grand-total"></span></h5>

                <form method="POST" action="{{ route('cashier.transaksi') }}" id="form-transaksi" class="mt-3">
                    @csrf
                    <div class="mb-2">
                        <label>Apakah mempunyai member?</label><br>
                        <button type="button" class="btn btn-success btn-sm" onclick="handleMember(true)">Iya</button>
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="handleMember(false)">Tidak</button>
                    </div>

                    <select class="form-control mb-2" id="customer_id" name="customer_id" required
                        style="display: none;">
                        <option value="" readonly selected>-- Pilih Customer --</option>
                        @foreach ($customers as $customer)
                        <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                        @endforeach
                    </select>

                    <input type="hidden" name="items" id="items-input">

                    <label for="method">Metode Pembayaran:</label>
                    <select name="method" class="form-control mb-2" required id="method">
                        <option value="cash" selected>Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="membership">Membership</option>
                    </select>

                    <div id="cash-fields" style="display:none;">
                        <label for="cash_received" id="uangditerima">Uang Diterima (Rp):</label>
                        <input type="number" id="cash-received" class="form-control" placeholder="Masukkan nominal"
                            min="0">
                        <label class="mt-2" id="kembalian-text">Kembalian:</label>
                        <input type="text" id="cash-change" class="form-control" readonly>
                    </div>

                    <button class="btn btn-primary mt-3 w-100" type="button" id="openConfirmModal">💰 Bayar</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
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
                <button type="button" class="btn btn-secondary" id="printNota">Print Nota</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="closeDetailModal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="scanMemberModal" tabindex="-1" role="dialog" aria-labelledby="scanMemberLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanMemberLabel">Scan Kartu Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> <!-- tanda silang -->
                </button>
            </div>
            <div class="modal-body">
                <label for="uidInput">Scan UID:</label>
                <input type="text" id="uidInput" class="form-control" placeholder="Scan UID di sini">
                <div id="memberInfo" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="scanBtn" onclick="scanUID()">Cari Member</button>
                <button type="button" class="btn btn-success d-none" id="confirmBtn"
                    data-dismiss="modal">Konfirmasi</button>
                <button type="button" class="btn btn-secondary" id="closeBtn" data-dismiss="modal">Tutup</button>
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
    let grandTotal = document.getElementById('grand-total');

    function toggleCashFields() {
        if (methodSelect.value === 'cash') {
            cashFields.style.display = 'block';
            cashReceived.disabled = false;
            cashChange.disabled = false;
            cashReceived.placeholder = 'Masukkan nominal';
        } else if (methodSelect.value === 'membership') {
            cashFields.style.display = 'block';
            cashReceived.disabled = true;
            cashChange.disabled = true;
            // Auto-fill dengan grand total
            const grandTotalText = document.getElementById('grand-total').innerText;
            const cleaned = grandTotalText.replace(/[^\d]/g, '');
            const grandTotalValue = parseFloat(cleaned);
            cashReceived.value = grandTotalValue || 0;
            cashReceived.placeholder = 'Otomatis sesuai total';
            cashChange.value = 'Saldo akan dipotong';
        } else {
            cashFields.style.display = 'none';
            cashReceived.value = '';
            cashChange.value = '';
            cashReceived.disabled = false;
            cashChange.disabled = false;
        }
    }

    methodSelect.addEventListener('change', toggleCashFields);
    document.addEventListener('DOMContentLoaded', function () {
        toggleCashFields();
    });

    cashReceived.addEventListener('input', function () {
        // Hanya aktif jika bukan membership
        if (methodSelect.value === 'membership') return;

        const received = parseFloat(this.value);
        const grandTotalText = document.getElementById('grand-total').innerText;

        // Hapus karakter non-digit (seperti "Rp", titik)
        const cleaned = grandTotalText.replace(/[^\d]/g, '');

        // Konversi ke angka
        const grandTotalValue = parseFloat(cleaned);

        // Hitung kembalian
        const change = received - grandTotalValue;

        cashChange.value = change >= 0 ? 'Rp ' + change.toLocaleString() : 'Rp 0';
    });

    document.getElementById('openConfirmModal').addEventListener('click', function () {
        const customerSelect = document.getElementById('customer_id');
        const customer = customerSelect.value;
        const itemsInput = document.getElementById('items-input').value;
        const method = document.querySelector('select[name="method"]').value;
        const cashReceived = document.getElementById('cash-received').value;

        // cek keranjang kosong
        let isItemsEmpty = true;
        try {
            const items = JSON.parse(itemsInput);
            isItemsEmpty = !items || items.length === 0;
        } catch (e) {
            isItemsEmpty = true;
        }

        // cek uang diterima untuk cash (untuk membership tidak perlu cek karena otomatis)
        const isCashEmpty = method === 'cash' && (!cashReceived || parseFloat(cashReceived) <= 0);

        // cek customer belum dipilih (default option disabled biasanya valuenya null atau '')
        const isCustomerEmpty = !customer || customer === '';

        // Validasi khusus untuk method membership
        if (method === 'membership' && isCustomerEmpty) {
            alert('Customer harus dipilih untuk pembayaran membership.');
            return;
        }

        // jika SEMUA kosong (hanya untuk cash)
        if (method === 'cash' && isCustomerEmpty && isItemsEmpty && isCashEmpty) {
            alert('Form belum diisi sama sekali! Pilih customer, tambahkan item, dan masukkan uang diterima.');
            return;
        }

        // cek satu-satu
        if (isCustomerEmpty) {
            alert('Silakan pilih customer terlebih dahulu.');
            return;
        }

        if (isItemsEmpty) {
            alert('Keranjang belanja masih kosong. Silakan tambahkan item.');
            return;
        }

        if (isCashEmpty) {
            alert('Masukkan nominal uang diterima untuk metode pembayaran cash.');
            return;
        }

        // kalau semua valid, submit form
        $('#confirmModal').modal('show');
    });

    document.getElementById('confirmSubmit').addEventListener('click', function () {
        const form = document.getElementById('form-transaksi');
        const formData = new FormData(form);

        // Debug: Log form data untuk memastikan customer_id ter-submit
        console.log('Form data sebelum submit:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Pastikan customer_id ter-submit dengan benar
        const customerSelect = document.getElementById('customer_id');
        const customerId = customerSelect.value;

        if (!customerId && document.querySelector('select[name="method"]').value === 'membership') {
            alert('Customer ID tidak ditemukan untuk pembayaran membership');
            return;
        }

        // Jika customer_id tidak ada di FormData, tambahkan manual
        if (!formData.get('customer_id') && customerId) {
            formData.set('customer_id', customerId);
        }

        const method = document.querySelector('select[name="method"]')?.value || 'cash';
        const orderType = method === 'membership' ? 'vip' : 'regular';
        formData.set('order_type', orderType);
        window.lastOrderType = orderType;

        console.log('Metode pembayaran:', method);
        console.log('Order type terdeteksi:', orderType);

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

                const method = document.querySelector('select[name="method"]').value;
                const uangDiterima = parseInt(document.getElementById('cash-received').value || '0');
                let kembalian = 0;

                const pajak = parseInt(document.getElementById('tax-amount')?.textContent.replace(/[^\d]/g, '') || 0);
                const jasa = parseInt(document.getElementById('service-amount')?.textContent.replace(/[^\d]/g, '') || 0);

                // Handle response yang berbeda untuk membership vs non-membership
                let totalAkhir;
                let orderDetails = [];

                if (method === 'membership') {
                    totalAkhir = order.total || data.total || 0;
                    orderDetails = order.order_details || [];
                    // Tampilkan sisa saldo jika ada
                    if (data.remaining_balance !== undefined) {
                        console.log('Sisa saldo setelah transaksi:', data.remaining_balance);
                    }
                } else {
                    if (typeof order.total === 'undefined') {
                        console.error('order.total tidak tersedia di response:', order);
                        alert('Data transaksi tidak lengkap (total).');
                        return;
                    }
                    totalAkhir = order.total;
                    orderDetails = order.order_details || [];
                }

                if (method === 'cash') {
                    kembalian = uangDiterima - totalAkhir;
                }

                console.log(['uang diterima', uangDiterima, 'kembalian', kembalian, 'totalakhir', totalAkhir]);

                let html = `
                <p><strong>Invoice:</strong> ${order?.invoice_no || 'N/A'}</p>
                <p><strong>Tanggal:</strong> ${order?.order_date ? new Date(order.order_date).toLocaleString() : new Date().toLocaleString()}</p>
                <p><strong>Total Produk:</strong> ${order?.total_products || 'N/A'}</p>
                <p><strong>Pajak:</strong> Rp ${pajak.toLocaleString()}</p>
                <p><strong>Total Harga:</strong> Rp ${totalAkhir.toLocaleString()}</p>
                <p><strong>Status Pembayaran:</strong> ${order?.payment_status || 'paid'}</p>
                `;

                if (method === 'cash') {
                    html += `
                        <p><strong>Uang Diterima:</strong> Rp ${uangDiterima.toLocaleString()}</p>
                        <p><strong>Kembalian:</strong> Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}</p>
                    `;
                }

                if (method === 'membership' && data.remaining_balance !== undefined) {
                    html += `
                        <p><strong>Sisa Saldo:</strong> Rp ${data.remaining_balance.toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        })}</p>
                    `;
                    // Simpan sisa saldo untuk print nota
                    window.lastMemberBalance = data.remaining_balance;
                }

                html += `
                    <hr>
                    <h5>Rincian Produk:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                orderDetails.forEach(detail => {
                    html += `
                        <tr>
                            <td>${detail.product.product_name}</td>
                            <td>${detail.quantity}</td>
                            <td>Rp ${detail.unitcost.toLocaleString()}</td>
                            <td>Rp ${detail.total.toLocaleString()}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                document.getElementById('detailModalBody').innerHTML = html;
                $('#detailModal').modal('show');
                window.lastOrderId = order?.id || data.order_id;
            } else {
                alert('Error: ' + (data.error || 'Terjadi kesalahan'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat memproses transaksi.');
        });
    });

    // Reload halaman setelah modal detail ditutup
    $('#btnCloseModal').on('click', function () {
        window.location.reload(true);
    });

    // Function untuk scan UID member
    function scanUID() {
        const uid = document.getElementById('uidInput').value.trim();
        const infoContainer = document.getElementById('memberInfo');
        const scanBtn = document.getElementById('scanBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const closeBtn = document.getElementById('closeBtn');

        if (!uid) {
            alert("UID tidak boleh kosong.");
            return;
        }

        scanBtn.disabled = true;
        scanBtn.innerText = "Mencari...";

        fetch(`/check-member/${encodeURIComponent(uid)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            scanBtn.disabled = false;
            scanBtn.innerText = "Cari Member";
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const select = document.getElementById('customer_id');
                const { id, nama, saldo } = data.data;

                // Set customer
                for (const option of select.options) {
                    if (option.value == id) {
                        option.selected = true;
                        select.disabled = true;
                        break;
                    }
                }

                // Set method ke membership
                document.getElementById('method').value = 'membership';

                // Trigger toggle untuk update cash fields
                toggleCashFields();

                infoContainer.innerHTML = `
                    <div class="alert alert-success">
                        <strong>${nama}</strong><br>
                        Saldo: Rp${parseInt(saldo).toLocaleString()}
                    </div>`;

                // Tampilkan tombol konfirmasi dan sembunyikan tombol tutup
                confirmBtn.classList.remove('d-none');
                closeBtn.classList.add('d-none');

            } else {
                infoContainer.innerHTML = `
                    <div class="alert alert-danger">
                        Member dengan UID <strong>${uid}</strong> tidak ditemukan.
                    </div>`;
                confirmBtn.classList.add('d-none');
                closeBtn.classList.remove('d-none');
            }
        })
        .catch(error => {
            scanBtn.disabled = false;
            scanBtn.innerText = "Cari Member";
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari member.');
            confirmBtn.classList.add('d-none');
            closeBtn.classList.remove('d-none');
        });
    }

    document.getElementById('scanMemberModal').addEventListener('shown.modal', function () {
        const input = document.getElementById('uidInput');
        input.value = '';
        input.focus();

        document.getElementById('memberInfo').innerHTML = '';
        document.getElementById('confirmBtn').classList.add('d-none');
        document.getElementById('closeBtn').classList.remove('d-none');
    });

    // Function untuk update grand total dan cash fields ketika ada perubahan
    function updateGrandTotalAndCashFields() {
        if (methodSelect.value === 'membership') {
            // Update cash received sesuai grand total
            const grandTotalText = document.getElementById('grand-total').innerText;
            const cleaned = grandTotalText.replace(/[^\d]/g, '');
            const grandTotalValue = parseFloat(cleaned);
            cashReceived.value = grandTotalValue || 0;
            cashChange.value = 'Saldo akan dipotong';
        }
    }

    // Observer untuk memantau perubahan grand total
    if (grandTotal) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    updateGrandTotalAndCashFields();
                }
            });
        });

        observer.observe(grandTotal, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }
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
        let subtotal = 0;

        cart.forEach((item, index) => {
            subtotal += item.subtotal;
            cartTableBody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${index})" title="Kurangi qty">
                            <i class="fa fa-minus"></i>
                        </button>
                        <span class="mx-2">${item.qty}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${index})" title="Tambah qty">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                    <td>Rp ${item.subtotal.toLocaleString()}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${index})" title="Hapus item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
        });

        // Pajak
        const taxPercentage = parseFloat(document.getElementById("tax-percentage").value) || 0;
        const baseForTax = subtotal;
        const taxAmount = baseForTax * taxPercentage / 100;
        const grandTotal = baseForTax + taxAmount;

        // Update tampilan
        totalDisplay.innerText = subtotal.toLocaleString();
        document.getElementById("tax-amount").innerText = taxAmount.toLocaleString();
        document.getElementById("grand-total").innerText = grandTotal.toLocaleString();

        // Simpan cart ke input hidden
        itemsInput.value = JSON.stringify(cart);
    }

        function increaseQty(index) {
        cart[index].qty += 1;
        cart[index].subtotal = cart[index].qty * cart[index].price;
        renderCart();
    }

    function decreaseQty(index) {
        if (cart[index].qty > 1) {
            cart[index].qty -= 1;
            cart[index].subtotal = cart[index].qty * cart[index].price;
        } else {
            cart.splice(index, 1); // Hapus item jika qty tinggal 1
        }
        renderCart();
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
<script>
    document.getElementById('closeDetailModal').addEventListener('click', function () {
    console.log(123);
    document.activeElement.blur(); // Lepaskan focus
    $('#detailModal').modal('hide');
    setTimeout(() => location.reload(), 500);
    });
</script>
<script>
    document.getElementById('printNota').addEventListener('click', function () {
        const orderId = window.lastOrderId;
        const orderType = window.lastOrderType || 'regular'; // 'vip' jika membership

        const methodSelect = document.querySelector('select[name="method"]');
        const cashReceived = document.getElementById('cash-received');
        const changeInput = document.getElementById('cash-change');
        const subtotalInput = document.getElementById('subtotal-amount');
        const taxPercentageInput = document.getElementById('tax-percentage');

        const pay = cashReceived?.value ? parseInt(cashReceived.value.replace(/[^\d]/g, '')) || 0 : 0;
        const change = changeInput?.value ? parseInt(changeInput.value.replace(/[^\d]/g, '')) || 0 : 0;
        const method = methodSelect?.value || 'Tidak diketahui';
        const subtotal = subtotalInput?.value ? parseInt(subtotalInput.value.replace(/[^\d]/g, '')) || 0 : 0;
        const taxPercent = taxPercentageInput?.value ? parseFloat(taxPercentageInput.value) || 0 : 0;

        const taxAmount = Math.round(subtotal * taxPercent / 100);

        // Untuk membership, ambil sisa saldo dari response yang tersimpan
        let remainingBalance = 0;
        if (method === 'membership' && window.lastMemberBalance !== undefined) {
            remainingBalance = window.lastMemberBalance;
        }

        const params = new URLSearchParams({
            pay,
            change,
            method,
            tax: taxAmount,
            type: orderType,
            remaining_balance: remainingBalance // tambahkan sisa saldo
        }).toString();

        const printWindow = window.open(`/print-nota/${orderId}?${params}`, 'Print Nota', 'width=300,height=600');
    });
</script>
<script>
    function handleMember(isMember) {
    const select = document.getElementById('customer_id');
    select.disabled = false;
    select.style.display = 'none'; // tampilkan select
    document.getElementById('memberInfo').innerHTML = '';

    if (isMember) {
        select.disabled = false;
        select.style.pointerEvents = 'auto';
        select.style.backgroundColor = '';
        // Tampilkan modal scan
        var myModal = new bootstrap.Modal(document.getElementById('scanMemberModal'));
            select.style.display = 'block'; // tampilkan select
        myModal.show();
    } else {
        // Pilih customer umum
        for (const option of select.options) {
            if (option.text.toLowerCase().includes('umum')) {
                option.selected = true;
                break;
            }
        }
        select.disabled = false; // jangan disable
        select.style.pointerEvents = 'none';
        select.style.backgroundColor = '#e9ecef';
        select.style.display = 'block'; // tampilkan select
    }
}
</script>
{{-- <script>
    function scanUID() {
    const uid = document.getElementById('uidInput').value.trim();
    const infoContainer = document.getElementById('memberInfo');
    const scanBtn = document.getElementById('scanBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const closeBtn = document.getElementById('closeBtn');

    if (!uid) {
        alert("UID tidak boleh kosong.");
        return;
    }

    scanBtn.disabled = true;
    scanBtn.innerText = "Mencari...";

    fetch(`/check-member/${encodeURIComponent(uid)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        scanBtn.disabled = false;
        scanBtn.innerText = "Cari Member";
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const select = document.getElementById('customer_id');
            const { id, nama, saldo } = data.data;

            for (const option of select.options) {
                if (option.value == id) {
                    option.selected = true;
                    select.disabled = true;
                    break;
                }
            }

              document.getElementById('method').value = 'membership';

            infoContainer.innerHTML = `
                <div class="alert alert-success">
                    <strong>${nama}</strong><br>
                    Saldo: Rp${parseInt(saldo).toLocaleString()}
                </div>`;

            // Tampilkan tombol konfirmasi dan sembunyikan tombol tutup
            confirmBtn.classList.remove('d-none');
            closeBtn.classList.add('d-none');

        } else {
            infoContainer.innerHTML = `
                <div class="alert alert-danger">
                    Member dengan UID <strong>${uid}</strong> tidak ditemukan.
                </div>`;
            confirmBtn.classList.add('d-none');
            closeBtn.classList.remove('d-none');
        }
    })
    .catch(error => {
        scanBtn.disabled = false;
        scanBtn.innerText = "Cari Member";
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mencari member.');
        confirmBtn.classList.add('d-none');
        closeBtn.classList.remove('d-none');
    });
}

document.getElementById('scanMemberModal').addEventListener('shown.modal', function () {
    const input = document.getElementById('uidInput');
    input.value = '';
    input.focus();

    document.getElementById('memberInfo').innerHTML = '';
    document.getElementById('confirmBtn').classList.add('d-none');
    document.getElementById('closeBtn').classList.remove('d-none');
});

</script> --}}
@endsection