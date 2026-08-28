@extends('layouts.app')
@section('title', 'Tambah Gudang')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <h5 class="card-title mb-3">Tambah Gudang</h5>
        <form method="POST" action="{{ route('warehouses.store') }}">
            @csrf
            @include('warehouses._form', ['warehouse' => null])
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
