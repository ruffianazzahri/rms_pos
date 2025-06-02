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
                <option value="cash">Cash</option>
                <option value="qris">QRIS</option>
                <option value="debit">Debit</option>
                <option value="credit">Credit</option>
                <option value="e-wallet">E-Wallet</option>
            </select>

            <button class="btn btn-primary mt-3" type="submit">💰 Bayar</button>
        </form>
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

@endsection