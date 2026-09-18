@extends('layouts.app')

@section('title', 'Barang Masuk Baru')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transaksi Barang Masuk</h4>
    <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<form method="POST" action="{{ route('stock-ins.store') }}">
    @csrf

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Gudang</label>
                    <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                {{ $warehouse->name }} ({{ $warehouse->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Supplier (opsional)</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Catatan (opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Barang</th>
                        <th>Stok Saat Ini (semua gudang)</th>
                        <th style="width:110px">Qty Masuk</th>
                        <th style="width:160px">Harga Beli / Unit</th>
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
                            <td>
                                <span class="badge bg-secondary">{{ $product->stock }} {{ $product->unit }}</span>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="qty[{{ $product->id }}]"
                                    min="0"
                                    value="{{ old('qty.'.$product->id, 0) }}"
                                    class="form-control form-control-sm qty-input"
                                    data-id="{{ $product->id }}"
                                >
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="cost[{{ $product->id }}]"
                                    min="0"
                                    value="{{ old('cost.'.$product->id, $product->cost) }}"
                                    class="form-control form-control-sm cost-input"
                                    data-id="{{ $product->id }}"
                                >
                            </td>
                            <td class="text-end subtotal-cell" data-id="{{ $product->id }}">Rp 0</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada barang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="fs-5 fw-bold">Total: <span id="grandTotal">Rp 0</span></div>
            <button type="submit" class="btn btn-success btn-lg">Simpan Transaksi</button>
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
        const id = input.dataset.id;
        const qty = parseInt(input.value) || 0;
        const costInput = document.querySelector(`.cost-input[data-id="${id}"]`);
        const cost = parseInt(costInput.value) || 0;
        const subtotal = qty * cost;

        document.querySelector(`.subtotal-cell[data-id="${id}"]`).innerText = formatRupiah(subtotal);
        total += subtotal;
    });

    document.getElementById('grandTotal').innerText = formatRupiah(total);
}

document.querySelectorAll('.qty-input, .cost-input').forEach(input => input.addEventListener('input', recalculate));

recalculate();
</script>
@endpush