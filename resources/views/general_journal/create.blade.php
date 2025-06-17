@extends('dashboard.body.main')

@section('container')
<div class="container">
    <h3>{{ isset($general_journal) ? 'Edit Journal Entry' : 'Add Journal Entry' }}</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form
        action="{{ isset($general_journal) ? route('general_journal.update', $general_journal->id) : route('general_journal.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($general_journal))
        @method('PUT')
        @endif

        <div class="form-group">
            <label>Date</label>
            <input type="date" class="form-control" value="{{ old('date', $general_journal->date ?? date('Y-m-d')) }}"
                disabled>
            <input type="hidden" name="date" value="{{ old('date', $general_journal->date ?? date('Y-m-d')) }}">
        </div>
        @php
        $accounts = [
        'Aset' => [
        'Kas' => 'Kas',
        'Bank' => 'Bank',
        'Piutang Usaha' => 'Piutang Usaha',
        'Persediaan Barang Dagang' => 'Persediaan Barang Dagang',
        'Peralatan' => 'Peralatan',
        'Aset Tetap Lainnya' => 'Aset Tetap Lainnya',
        ],
        'Liabilitas & Ekuitas' => [
        'Utang Usaha' => 'Utang Usaha',
        'Modal Pemilik' => 'Modal Pemilik',
        'Prive' => 'Prive',
        'Suntikan Dana' => 'Suntikan Dana',
        ],
        'Pendapatan' => [
        'Penjualan' => 'Penjualan',
        'Pendapatan Lain-lain' => 'Pendapatan Lain-lain',
        'Diskon Penjualan' => 'Diskon Penjualan',
        ],
        'Beban' => [
        'Harga Pokok Penjualan' => 'Harga Pokok Penjualan',
        'Beban Operasional' => 'Beban Operasional',
        'Beban Gaji' => 'Beban Gaji',
        'Beban Listrik & Air' => 'Beban Listrik & Air',
        'Beban Sewa' => 'Beban Sewa',
        'Beban Transportasi' => 'Beban Transportasi',
        'Beban Penyusutan' => 'Beban Penyusutan',
        'Pengeluaran Tidak Terduga' => 'Pengeluaran Tidak Terduga',
        ],
        'Lain-lain' => [
        'Uang Tip' => 'Uang Tip',
        ],
        ];

        $selectedAccount = old('account', $general_journal->account ?? '');
        @endphp

        <div class="form-group">
            <label>Akun</label>
            <select name="account" id="account" class="form-control" required>
                <option value="" disabled {{ $selectedAccount=='' ? 'selected' : '' }}>-- Pilih Akun --</option>
                @foreach ($accounts as $group => $groupAccounts)
                <optgroup label="{{ $group }}">
                    @foreach ($groupAccounts as $key => $label)
                    <option value="{{ $key }}" {{ $selectedAccount==$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Pemasukan</label>
            <input type="text" name="credit" id="credit" class="form-control format-number"
                value="{{ old('credit', $general_journal->credit ?? '') }}">
        </div>

        <div class="form-group">
            <label>Pengeluaran</label>
            <input type="text" name="debit" id="debit" class="form-control format-number"
                value="{{ old('debit', $general_journal->debit ?? '') }}">
        </div>


        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" class="form-control"
                value="{{ old('description', $general_journal->description ?? '') }}" maxlength="255">
        </div>

        <div class="form-group">
            <label>Order ID (optional)</label>
            <input type="number" name="order_id" class="form-control"
                value="{{ old('order_id', $general_journal->order_id ?? '') }}">
        </div>

        <div class="form-group">
            <label>Upload Bukti (foto resi, struk, dll) <span class="text-danger">*</span></label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required
                accept="image/*">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">{{ isset($general_journal) ? 'Update' : 'Save' }}</button>
    </form>
</div>

@endsection

@section('scripts')
<script>
    console.log(123);
    document.addEventListener('DOMContentLoaded', function() {
        const debitInput = document.querySelector('input[name="debit"]');
        const creditInput = document.querySelector('input[name="credit"]');

        function toggleInputs() {
            if (debitInput.value && parseFloat(debitInput.value) > 0) {
                creditInput.disabled = true;
                creditInput.value = '';
            } else if (creditInput.value && parseFloat(creditInput.value) > 0) {
                debitInput.disabled = true;
                debitInput.value = '';
            } else {
                debitInput.disabled = false;
                creditInput.disabled = false;
            }
        }

        debitInput.addEventListener('input', toggleInputs);
        creditInput.addEventListener('input', toggleInputs);

        toggleInputs();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.format-number');

    function formatNumber(value) {
        // Hapus semua karakter kecuali angka dan titik (desimal)
        value = value.replace(/[^0-9.]/g, '');
        // Pisahkan integer dan desimal
        let parts = value.split('.');
        // Format integer dengan ribuan
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return parts.join('.');
    }

    inputs.forEach(input => {
        // Format saat input sudah di-load
        if (input.value) {
            input.value = formatNumber(input.value.toString());
        }

        // Saat user mengetik, format tampilan
        input.addEventListener('input', function (e) {
            let cursorPosition = this.selectionStart;
            let originalLength = this.value.length;

            this.value = formatNumber(this.value);

            let newLength = this.value.length;
            cursorPosition = cursorPosition + (newLength - originalLength);
            this.setSelectionRange(cursorPosition, cursorPosition);
        });

        // Saat form disubmit, hapus format agar value bersih dikirim
        input.closest('form').addEventListener('submit', function () {
            inputs.forEach(i => {
                i.value = i.value.replace(/,/g, '');
            });
        });
    });
});

</script>
@endsection