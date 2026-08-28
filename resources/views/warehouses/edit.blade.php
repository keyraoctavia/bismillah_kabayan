@extends('layouts.app')
@section('title', 'Edit Gudang')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <h5 class="card-title mb-3">Edit Gudang</h5>
        <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
            @csrf @method('PUT')
            @include('warehouses._form')
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
