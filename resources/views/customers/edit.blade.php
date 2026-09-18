@extends('layouts.app')
@section('title', 'Edit Pelanggan')

@section('content')
<h4 class="mb-3">Edit Pelanggan</h4>
<form method="POST" action="{{ route('customers.update', $customer) }}">
    @csrf
    @method('PUT')
    @include('customers._form', ['customer' => $customer])
</form>
@endsection