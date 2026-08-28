<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Kasir')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('cashier.index') }}">PT Sinar Nusantara</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cashier.index') ? 'active fw-bold' : '' }}" href="{{ route('cashier.index') }}">Kasir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('stock-ins.*') ? 'active fw-bold' : '' }}" href="{{ route('stock-ins.index') }}">Barang Masuk</a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('warehouses.*') ? 'active fw-bold' : '' }}" href="{{ route('warehouses.index') }}">Gudang</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active fw-bold' : '' }}" href="{{ route('transactions.index') }}">
                            <i class="bi bi-receipt"></i> Riwayat Transaksi
                        </a>
                    </li>
                        
                    @endif
                    @endauth
                </ul>
                @auth
                <ul class="navbar-nav ms-auto align-items-md-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            {{ auth()->user()->name }}
                            <span class="badge {{ auth()->user()->isAdmin() ? 'bg-warning text-dark' : 'bg-info text-dark' }}">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'Operator' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
