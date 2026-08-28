@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <h5 class="card-title mb-3">Edit Produk</h5>
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('products._form')
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
