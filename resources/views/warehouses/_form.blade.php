<div class="mb-3">
    <label class="form-label">Kode Gudang</label>
    <input type="text" name="code" value="{{ old('code', $warehouse->code ?? '') }}" class="form-control @error('code') is-invalid @enderror">
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama Gudang</label>
    <input type="text" name="name" value="{{ old('name', $warehouse->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $warehouse->address ?? '') }}</textarea>
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
