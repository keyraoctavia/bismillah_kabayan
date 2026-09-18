@extends('layouts.app')
@section('title', 'Barang Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transaksi Barang Masuk</h4>
    <a href="{{ route('stock-ins.create') }}" class="btn btn-primary">+ Barang Masuk Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari no. referensi / supplier...">
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Referensi</th>
                    <th>Tanggal</th>
                    <th>Gudang</th>
                    <th>Supplier</th>
                    <th>Diinput oleh</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockIns as $stockIn)
                    <tr>
                        <td>{{ $stockIn->reference_number }}</td>
                        <td>{{ $stockIn->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $stockIn->warehouse->name ?? '-' }}</td>
                        <td>{{ $stockIn->supplier ?? '-' }}</td>
                        <td>{{ $stockIn->user->name ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($stockIn->total_cost, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $stockIn->status === 'dibatalkan' ? 'bg-danger' : 'bg-success' }}">
                                {{ ucfirst($stockIn->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('stock-ins.show', $stockIn) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @if($stockIn->status !== 'dibatalkan' && auth()->user()->isAdmin())
                                <form action="{{ route('stock-ins.cancel', $stockIn) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Batalkan transaksi barang masuk ini? Stok akan dikurangi kembali.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi barang masuk</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $stockIns->links() }}
    </div>
</div>
@endsection