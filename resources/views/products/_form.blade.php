<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">SKU / Kode Barang</label>
        <input type="text" name="code" value="{{ old('code', $product->code ?? '') }}" class="form-control @error('code') is-invalid @enderror">
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" class="form-control @error('category') is-invalid @enderror" placeholder="Contoh: Baju,Jaket,Dll">
        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="form-control @error('unit') is-invalid @enderror" placeholder="Contoh: pcs, kg, box">
        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Harga Pokok</label>
        <input type="number" name="cost" value="{{ old('cost', $product->cost ?? '') }}" class="form-control @error('cost') is-invalid @enderror">
        @error('cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Harga Jual</label>
        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control @error('price') is-invalid @enderror">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" class="form-control @error('stock') is-invalid @enderror">
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Gambar Produk (opsional)</label>
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if(!empty($product) && $product->image)
        <img src="{{ asset('storage/' . $product->image) }}" width="80" class="mt-2 rounded">
    @endif
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Status Aktif</label>
</div>
