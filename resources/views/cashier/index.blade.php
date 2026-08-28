@extends('layouts.app')

@section('title', 'Kasir PT Sinar Nusantara')

@section('content')

@if(session('receipt'))
    @php($receipt = session('receipt'))
    <div class="card shadow-sm mb-3 border-success">
        <div class="card-body">
            <h5 class="card-title text-success mb-1">Transaksi Berhasil</h5>
            <p class="text-muted mb-3">No. Invoice: <strong>{{ $receipt['invoice_number'] }}</strong></p>
            <table class="table table-sm mb-3">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt['items'] as $line)
                        <tr>
                            <td>{{ $line['name'] }}</td>
                            <td class="text-end">{{ $line['qty'] }}</td>
                            <td class="text-end">Rp {{ number_format($line['price'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($line['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between"><span>Total</span><strong>Rp {{ number_format($receipt['total_price'], 0, ',', '.') }}</strong></div>
            <div class="d-flex justify-content-between"><span>Dibayar</span><strong>Rp {{ number_format($receipt['cash'], 0, ',', '.') }}</strong></div>
            <div class="d-flex justify-content-between"><span>Kembalian</span><strong>Rp {{ number_format($receipt['change'], 0, ',', '.') }}</strong></div>
        </div>
    </div>
@endif

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('cashier.index') }}" class="row g-2">
            <div class="col-md-11">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / kode produk...">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('cashier.checkout') }}">
    @csrf

    <div class="card shadow-sm mb-3">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th style="width:110px">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                {{ $product->name }}
                                <br><small class="text-muted">{{ $product->code }}</small>
                            </td>
                            <td>{{ $product->formatted_price }}</td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="qty[{{ $product->id }}]"
                                    min="0"
                                    max="{{ $product->stock }}"
                                    value="{{ old('qty.'.$product->id, 0) }}"
                                    class="form-control form-control-sm qty-input"
                                    data-price="{{ $product->price }}"
                                    data-id="{{ $product->id }}"
                                    {{ $product->stock == 0 ? 'disabled' : '' }}
                                >
                            </td>
                            <td class="text-end subtotal-cell" data-id="{{ $product->id }}">Rp 0</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Produk tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
                <span>Total</span>
                <span id="grandTotal">Rp 0</span>
            </div>

            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Uang Dibayar</label>
                    <input type="number" name="cash" id="cashInput" class="form-control form-control-lg" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kembalian</label>
                    <div class="form-control form-control-lg bg-light" id="changeAmount">Rp 0</div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function formatRupiah(number) {
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

function recalculate() {
    let total = 0;

    document.querySelectorAll('.qty-input').forEach(input => {
        const qty = parseInt(input.value) || 0;
        const price = parseInt(input.dataset.price) || 0;
        const subtotal = qty * price;

        document.querySelector(`.subtotal-cell[data-id="${input.dataset.id}"]`).innerText = formatRupiah(subtotal);
        total += subtotal;
    });

    document.getElementById('grandTotal').innerText = formatRupiah(total);

    const cash = parseInt(document.getElementById('cashInput').value) || 0;
    const change = cash - total;
    document.getElementById('changeAmount').innerText = formatRupiah(change > 0 ? change : 0);
}

document.querySelectorAll('.qty-input').forEach(input => input.addEventListener('input', recalculate));
document.getElementById('cashInput').addEventListener('input', recalculate);

recalculate();
</script>
@endpush
