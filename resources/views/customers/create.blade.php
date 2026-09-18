@extends('layouts.app')
@section('title', 'Tambah Pelanggan')

@section('content')
<h4 class="mb-3">Tambah Pelanggan</h4>
<form method="POST" action="{{ route('customers.store') }}">
    @csrf
    @include('customers._form', ['customer' => null])
</form>
@endsection