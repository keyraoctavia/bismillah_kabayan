@extends('layouts.app')
@section('title', 'Detail Transfer')

@section('content')
<h4 class="mb-3">Detail Transfer {{ $stockTransfer->reference_number }}</h4>
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <p><strong>Dari:</strong> {{ $stockTransfer->fromWarehouse->name }} &nbsp; <strong>Ke:</strong> {{ $stockTransfer->toWarehouse->name }}</p>
        <p><strong>Oleh:</strong> {{ $stockTransfer->user->name ?? '-' }} pada {{ $stockTransfer->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($stockTransfer->status) }}</p>
        <table class="table">
            <thead><tr><th>Barang</th><th>Qty</th></tr></thead>
            <tbody>
                @foreach($stockTransfer->details as $d)
                    <tr><td>{{ $d->product->name }}</td><td>{{ $d->qty }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection