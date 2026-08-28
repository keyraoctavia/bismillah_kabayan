@extends('layouts.app')

@section('title', 'Detail Barang Masuk')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Detail Transaksi Barang Masuk</h4>
    <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>No. Referensi:</strong> {{ $stockIn->reference_number }}</p>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $stockIn->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-1"><strong>Gudang:</strong> {{ $stockIn->warehouse->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Supplier:</strong> {{ $stockIn->supplier ?? '-' }}</p>
                <p class="mb-1"><strong>Diinput oleh:</strong> {{ $stockIn->user->name ?? '-' }}</p>
                <p class="mb-1"><strong>Catatan:</strong> {{ $stockIn->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Barang</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga Beli</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockIn->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name ?? '-' }}</td>
                        <td class="text-end">{{ $detail->qty }} {{ $detail->product->unit ?? '' }}</td>
                        <td class="text-end">Rp {{ number_format($detail->cost, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total</td>
                    <td class="text-end">Rp {{ number_format($stockIn->total_cost, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
