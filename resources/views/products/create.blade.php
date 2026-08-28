@extends('layouts.app')
@section('title','Tambah Produk')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <h5 class="card-title mb-3">Tambah Produk</h5>
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('products._form',['product'=> null])
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('products.index') }}"class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection    