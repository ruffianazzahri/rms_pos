@extends('dashboard.body.main')

@section('specificpagestyles')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container">
    <h2>🧾 Sistem Kasir RMS Batam</h2>
    <p class="fw-bold">Jika salah satu produk tidak muncul, kemungkinan stok habis. Harap cek stok berkala <a
            href="{{ route('products.index') }}" target="_blank">
            disini.
        </a></p>
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

                {{-- Tampilkan gambar produk --}}
                <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; display: block; margin: 0 auto 5px;">

                {{-- Nama dan harga --}}
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
        <input type="hidden" id="tax-percentage" value="{{ $restaurant_tax }}">
        <input type="hidden" id="service-percentage" value="{{ $service_charge }}">
        <h5>Subtotal: Rp <span id="total">0</span></h5>
        <h5>Jasa Pelayanan (10%): Rp <span id="service-amount">0</span></h5>
        <h5>Pajak Restoran (10%): Rp <span id="tax-amount">0</span></h5>
        <h5>Total Bayar: Rp <span id="grand-total"></span></h5>
        {{-- Pembayaran --}}
        <form method="POST" action="{{ route('cashier.transaksi') }}" id="form-transaksi">
            @csrf
            <div class="mb-2">
                <label>Apakah mempunyai member?</label><br>
                <button type="button" class="btn btn-success btn-sm"
                    onclick="alert('Fitur ini segera hadir!')">Iya</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="handleMember(false)">Tidak</button>
            </div>

            <select class="form-control" id="customer_id" name="customer_id" required style="display: none;">
                <option value="" readonly selected>-- Select Customer --</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
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
    <div class="modal-dialog  modal-xl" role="document">
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
                <button type="button" class="btn btn-primary" onclick="scanUID()">Cari Member</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
        } else {
            cashFields.style.display = 'none';
            cashReceived.value = '';
            cashChange.value = '';
        }
    }

    methodSelect.addEventListener('change', toggleCashFields);
    document.addEventListener('DOMContentLoaded', function () {
        toggleCashFields();
    });
        cashReceived.addEventListener('input', function () {
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

            // cek uang diterima untuk cash
            const isCashEmpty = method === 'cash' && (!cashReceived || parseFloat(cashReceived) <= 0);

            // cek customer belum dipilih (default option disabled biasanya valuenya null atau '')
            const isCustomerEmpty = !customer;

            // jika SEMUA kosong
            if (isCustomerEmpty && isItemsEmpty && method === 'cash' && isCashEmpty) {
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

                if (typeof order.total === 'undefined') {
                    console.error('order.total tidak tersedia di response:', order);
                    alert('Data transaksi tidak lengkap (total).');
                    return;
                }

            const totalAkhir = order.total + pajak + jasa;

            if (method === 'cash') {
                kembalian = uangDiterima - order.total;
            }

            let html = `
            <p><strong>Invoice:</strong> ${order.invoice_no}</p>
            <p><strong>Tanggal:</strong> ${new Date(order.order_date).toLocaleString()}</p>
            <p><strong>Total Produk:</strong> ${order.total_products}</p>
            <p><strong>Total Harga:</strong> Rp ${order.total.toLocaleString()}</p>
            <p><strong>Pajak:</strong> Rp ${pajak.toLocaleString()}</p>
            <p><strong>Jasa:</strong> Rp ${jasa.toLocaleString()}</p>
            <p><strong>Total Akhir:</strong> Rp ${totalAkhir.toLocaleString()}</p>
            <p><strong>Status Pembayaran:</strong> ${order.payment_status}</p>
            `;

            if (method === 'cash') {
                html += `
                    <p><strong>Uang Diterima:</strong> Rp ${uangDiterima.toLocaleString()}</p>
                    <p><strong>Kembalian:</strong> Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}</p>
                `;
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

            order.order_details.forEach(detail => {
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
              window.lastOrderId = order.id;
        }
    })

        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan.');
        });
    });

// Reload halaman setelah modal detail ditutup
  $('#btnCloseModal').on('click', function () {
      window.location.reload(true);
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
        let subtotal = 0;

        cart.forEach((item, index) => {
            subtotal += item.subtotal;
            cartTableBody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>Rp ${item.subtotal.toLocaleString()}</td>
                    <td><button onclick="removeItem(${index})">Hapus</button></td>
                </tr>`;
        });

        // Ambil persen pajak dan service dari input hidden
        const taxPercentage = parseFloat(document.getElementById("tax-percentage").value) || 0;
        const servicePercentage = parseFloat(document.getElementById("service-percentage").value) || 0;

        // === Hitung sesuai urutan yang benar ===
        const serviceAmount = subtotal * servicePercentage / 100;
        const baseForTax = subtotal + serviceAmount;
        const taxAmount = baseForTax * taxPercentage / 100;
        const grandTotal = baseForTax + taxAmount;

        // Tampilkan hasil
        totalDisplay.innerText = subtotal.toLocaleString();
        document.getElementById("service-amount").innerText = serviceAmount.toLocaleString();
        document.getElementById("tax-amount").innerText = taxAmount.toLocaleString();
        document.getElementById("grand-total").innerText = grandTotal.toLocaleString();

        // Simpan data cart ke input hidden
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

    const methodSelect = document.querySelector('select[name="method"]');
    const cashReceived = document.getElementById('cash-received');
    const changeInput = document.getElementById('cash-change');
    const subtotalInput = document.getElementById('subtotal-amount'); // ⬅️ Tambahkan input ini di HTML
    const servicePercentageInput = document.getElementById('service-percentage');
    const taxPercentageInput = document.getElementById('tax-percentage');

    // Ambil nilai dengan aman
    const pay = cashReceived?.value ? parseInt(cashReceived.value.replace(/[^\d]/g, '')) || 0 : 0;
    const change = changeInput?.value ? parseInt(changeInput.value.replace(/[^\d]/g, '')) || 0 : 0;
    const method = methodSelect?.value || 'Tidak diketahui';

    const subtotal = subtotalInput?.value ? parseInt(subtotalInput.value.replace(/[^\d]/g, '')) || 0 : 0;
    const servicePercent = servicePercentageInput?.value ? parseFloat(servicePercentageInput.value) || 0 : 0;
    const taxPercent = taxPercentageInput?.value ? parseFloat(taxPercentageInput.value) || 0 : 0;

    // Hitung ulang sesuai rumus yang benar
    const serviceAmount = Math.round(subtotal * servicePercent / 100);
    const taxBase = subtotal + serviceAmount;
    const taxAmount = Math.round(taxBase * taxPercent / 100);

    // Susun parameter URL
    const params = new URLSearchParams({
        pay: pay,
        change: change,
        method: method,
        tax: taxAmount,
        service: serviceAmount
    }).toString();

    // Buka jendela untuk cetak nota
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
<script>
    function scanUID() {
    const uid = document.getElementById('uidInput').value.trim();
    if (uid === "") {
        alert("UID tidak boleh kosong.");
        return;
    }

    fetch(`/check-member/${uid}`)
        .then(response => response.json())
        .then(data => {

            const select = document.getElementById('customer_id');
            if (data.success) {
                // Set nama member di select option
                for (const option of select.options) {
                    if (option.value == data.member.id) {
                        option.selected = true;
                        select.disabled = true;
                        break;
                    }
                }

                document.getElementById('memberInfo').innerHTML = `
                    <div class="alert alert-success">
                        <strong>${data.member.name}</strong><br>
                        Saldo: Rp${data.member.balance.toLocaleString()}
                    </div>`;
            } else {
                document.getElementById('memberInfo').innerHTML = `
                    <div class="alert alert-danger">Member dengan UID <strong>${uid}</strong> tidak ditemukan.</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari member.');
        });
}
</script>
@endsection