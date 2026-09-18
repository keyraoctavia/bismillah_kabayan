@extends('layouts.app')
@section('title', 'Transfer Barang Antar Gudang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transfer Barang Antar Gudang</h4>
    <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">+ Transfer Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Referensi</th>
                    <th>Tanggal</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Oleh</th>
                    <th>Status</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->reference_number }}</td>
                        <td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transfer->fromWarehouse->name }}</td>
                        <td>{{ $transfer->toWarehouse->name }}</td>
                        <td>{{ $transfer->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $transfer->status === 'dibatalkan' ? 'bg-danger' : 'bg-success' }}">
                                {{ ucfirst($transfer->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('stock-transfers.show', $transfer) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @if($transfer->status !== 'dibatalkan' && auth()->user()->isAdmin())
                                <form action="{{ route('stock-transfers.cancel', $transfer) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan transfer ini? Stok akan dikembalikan.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada transfer barang</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $transfers->links() }}
    </div>
</div>
@endsection