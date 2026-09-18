@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Total Barang</div>
                <div class="fs-3 fw-bold">{{ number_format($totalBarang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Total Gudang</div>
                <div class="fs-3 fw-bold">{{ number_format($totalGudang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Total Pelanggan</div>
                <div class="fs-3 fw-bold">{{ number_format($totalPelanggan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Total Penjualan Hari Ini</div>
                <div class="fs-4 fw-bold">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>Barang dengan Stok Terendah</strong>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">Foto</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th class="text-end">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangStokTerendah as $barang)
                    <tr>
                        <td>
                            @if($barang->image)
                                <img src="{{ asset('storage/' . $barang->image) }}"
                                     alt="{{ $barang->name }}"
                                     width="40" height="40"
                                     class="rounded border"
                                     style="object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted"
                                     style="width:40px; height:40px; font-size:9px;">
                                    N/A
                                </div>
                            @endif
                        </td>
                        <td>{{ $barang->code }}</td>
                        <td>{{ $barang->name }}</td>
                        <td class="text-end">
                            <span class="badge {{ $barang->stock <= 5 ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $barang->stock }} {{ $barang->unit }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada barang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection