@extends('layouts.app')
@section('title', 'Master Gudang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Master Gudang</h4>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">+ Tambah Gudang</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode / nama gudang...">
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->code }}</td>
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->address ?? '-' }}</td>
                        <td>
                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus gudang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada gudang</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $warehouses->links() }}
    </div>
</div>
@endsection
