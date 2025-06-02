<form method="POST" action="{{ isset($payment) ? route('payments.update', $payment) : route('payments.store') }}">
    @csrf
    @if(isset($payment)) @method('PUT') @endif

    <label>Order:</label>
    <select name="order_id">
        @foreach($orders as $order)
        <option value="{{ $order->id }}" {{ (old('order_id', $payment->order_id ?? '') == $order->id) ? 'selected' : ''
            }}>
            Order #{{ $order->id }}
        </option>
        @endforeach
    </select>

    <label>Metode:</label>
    <select name="method">
        @foreach(['cash', 'qris', 'debit', 'credit', 'e-wallet'] as $method)
        <option value="{{ $method }}" {{ (old('method', $payment->method ?? '') == $method) ? 'selected' : '' }}>
            {{ ucfirst($method) }}
        </option>
        @endforeach
    </select>

    <label>Jumlah:</label>
    <input type="number" step="0.01" name="amount" value="{{ old('amount', $payment->amount ?? '') }}" />

    <label>Status:</label>
    <select name="status">
        @foreach(['paid', 'unpaid', 'failed'] as $status)
        <option value="{{ $status }}" {{ (old('status', $payment->status ?? '') == $status) ? 'selected' : '' }}>
            {{ ucfirst($status) }}
        </option>
        @endforeach
    </select>

    <button type="submit">Simpan</button>
</form>