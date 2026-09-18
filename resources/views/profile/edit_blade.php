@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<h4 class="mb-3">Profil Saya</h4>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Profil</h5>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" value="{{ $user->isAdmin() ? 'Admin' : 'Operator' }}" class="form-control" disabled>
                        <small class="text-muted">Role hanya bisa diubah oleh admin lain melalui database.</small>
                    </div>

                    <button class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Ganti Password</h5>

                <form method="POST" action="{{ route('password_update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                        @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                        @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-danger">
            <div class="card-body">
                <h5 class="card-title mb-2 text-danger">Hapus Akun</h5>
                <p class="text-muted small">Tindakan ini tidak bisa dibatalkan. Semua data login akun ini akan dihapus permanen.</p>

                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Yakin ingin menghapus akun ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">Hapus Akun Saya</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection