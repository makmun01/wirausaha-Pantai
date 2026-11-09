@extends('layouts.admin')

@section('title', 'Tambah Produk UMKM Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <h4 class="fw-bold brand-font text-primary mb-3">Tambah Produk Baru ke Etalase</h4>
            <p class="text-muted small mb-4">Pastikan data harga dan stok terisi dengan benar (Mendukung pengujian batas BVA & EP).</p>

            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <input type="hidden" name="umkm_merchant_id" value="{{ $merchant->id ?? 1 }}">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Kerupuk Ikan Tenggiri Gurih" value="{{ old('name') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategori Produk</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Satuan Ukuran</label>
                        <input type="text" name="unit" class="form-control" placeholder="Contoh: pcs / bungkus / kg" value="{{ old('unit', 'bungkus') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Harga Satuan (IDR)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="price" class="form-control" placeholder="25000" min="0" value="{{ old('price') }}" required>
                        </div>
                        <small class="text-muted" style="font-size:0.75rem;">Nilai valid: minimal Rp 0</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Stok Awal</label>
                        <input type="number" name="stock" class="form-control" placeholder="50" min="0" value="{{ old('stock', 20) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Batas Stok Menipis</label>
                        <input type="number" name="low_stock_threshold" class="form-control" placeholder="5" min="1" value="{{ old('low_stock_threshold', 5) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Deskripsi Produk</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan komposisi bahan, rasa, dan keunggulan produk...">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-ocean rounded-pill px-4 fw-bold">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
