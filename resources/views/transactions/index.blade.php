@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<h4 class="mb-3"><i class="bi bi-receipt"></i> Riwayat Transaksi</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3 row g-2">
            <div class="col-md-3">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Invoice</th>
                    <th>Kasir</th>
                    <th>Keterangan Item</th> 
                    <th>Tanggal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td>{{ $trx->invoice_number }}</td>
                        <td>{{ $trx->user->name ?? 'Kasir tidak ditemukan' }}</td>
                        
                        
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($trx->details as $detail)
                                    <li>- {{ $detail->product->name }} ({{ $detail->qty }})</li>
                                @endforeach
                            </ul>
                        </td>
                        
                        <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $transactions->links() }}
    </div>
</div>
@endsection
