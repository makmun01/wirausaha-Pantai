@extends('layouts.public')

@section('title', $product->name . ' - Jumiang UMKM')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Katalog UMKM</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image Placeholder -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <i class="bi bi-box-seam display-1 text-primary mb-3"></i>
                <span class="badge bg-primary px-3 py-2">FOTO PRODUK UMKM [DEMO]</span>
            </div>
        </div>

        <!-- Product Actions & Details -->
        <div class="col-lg-6">
            <div class="card card-custom p-4">
                <span class="badge bg-info bg-opacity-10 text-primary border align-self-start mb-2">
                    {{ $product->category->name }}
                </span>
                <h2 class="fw-bold mb-2">{{ $product->name }}</h2>
                <p class="text-muted small mb-3">
                    <i class="bi bi-shop me-1 text-secondary"></i> Dijual oleh: <strong class="text-dark">{{ $product->merchant->business_name }}</strong> (Pemilik: {{ $product->merchant->owner_name }})
                </p>

                <div class="p-3 bg-light rounded-3 mb-4">
                    <span class="small text-muted d-block">Harga Satuan</span>
                    <span class="display-6 fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-muted">/ {{ $product->unit }}</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                    <p class="text-secondary leading-relaxed">
                        {{ $product->description ?? 'Produk olahan berkualitas khas Pantai Jumiang, diproduksi langsung oleh pelaku UMKM lokal Desa Tanjung secara higienis dan terjamin.' }}
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3">
                            <span class="small text-muted d-block">Stok Tersedia</span>
                            <span class="fw-bold fs-5 {{ $product->stock > 5 ? 'text-success' : 'text-danger' }}">
                                {{ $product->stock }} {{ $product->unit }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3">
                            <span class="small text-muted d-block">Lokasi Gerai</span>
                            <span class="fw-bold small text-dark d-block text-truncate">{{ $product->merchant->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Form / Direct Checkout -->
                <form action="{{ route('checkout') }}" method="GET" class="d-flex gap-3">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div style="width: 100px;">
                        <input type="number" name="quantity" class="form-control form-control-lg text-center" value="1" min="1" max="{{ $product->stock }}">
                    </div>
                    <a href="{{ route('cart') }}" class="btn btn-ocean btn-lg flex-grow-1 rounded-pill fw-bold">
                        <i class="bi bi-cart3 me-2"></i> Tambah ke Keranjang
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
