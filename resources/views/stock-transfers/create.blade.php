@extends('layouts.app')
@section('title', 'Transfer Barang Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transfer Barang Antar Gudang</h4>
    <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<form method="POST" action="{{ route('stock-transfers.store') }}">
    @csrf
    <div class="card shadow-sm mb-3">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">Gudang Asal</label>
                <select name="from_warehouse_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" @selected(old('from_warehouse_id') == $w->id)>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gudang Tujuan</label>
                <select name="to_warehouse_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" @selected(old('to_warehouse_id') == $w->id)>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Catatan (opsional)</label>
                <input type="text" name="notes" value="{{ old('notes') }}" class="form-control">
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Barang</th><th style="width:130px">Qty Transfer</th></tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }} <br><small class="text-muted">{{ $product->code }}</small></td>
                            <td>
                                <input type="number" name="qty[{{ $product->id }}]" min="0"
                                       value="{{ old('qty.'.$product->id, 0) }}" class="form-control form-control-sm">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Simpan Transfer</button>
</form>
@endsection