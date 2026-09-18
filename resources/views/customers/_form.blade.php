<div class="card shadow-sm mb-3">
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}"
                   class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Nomor HP (opsional)</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                   class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-12">
            <label class="form-label">Alamat (opsional)</label>
            <textarea name="address" rows="3"
                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address ?? '') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<button type="submit" class="btn btn-success">Simpan</button>
<a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>