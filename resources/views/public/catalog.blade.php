@extends('layouts.public')

@section('title', 'Katalog Produk UMKM - Oleh-Oleh & Batik Pantai Jumiang')

@section('content')
<div class="bg-primary text-white py-4" style="background: linear-gradient(135deg, #0284c7, #075985);">
    <div class="container py-2 text-center text-md-start">
        <h1 class="display-6 fw-bold mb-1">Marketplace Produk UMKM Jumiang</h1>
        <p class="lead opacity-90 mb-0 small">Mendukung produk olahan laut lokal, rengginang lorjuk, dan batik tulis pesisir Pamekasan.</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="card card-custom p-3 sticky-top" style="top: 90px;">
                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-funnel me-1"></i> Filter Pencarian</h6>
                
                <form action="{{ route('catalog') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari produk..." value="{{ request('q') }}">
                        <button class="btn btn-ocean btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <h6 class="fw-bold mb-2 text-secondary small text-uppercase">Kategori Produk</h6>
                <div class="list-group list-group-flush mb-3">
                    <a href="{{ route('catalog') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-1 {{ !$categoryId ? 'fw-bold text-primary' : 'text-secondary' }}">
                        <span>Semua Kategori</span>
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('catalog', ['category' => $cat->id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-1 {{ $categoryId == $cat->id ? 'fw-bold text-primary' : 'text-secondary' }}">
                            <span><i class="{{ $cat->icon ?? 'bi bi-tag' }} me-2 text-info"></i> {{ $cat->name }}</span>
                            <span class="badge bg-light text-secondary border">{{ $cat->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">Menampilkan <strong>{{ $products->total() }}</strong> produk aktif</span>
                <span class="badge bg-warning text-dark"><i class="bi bi-shield-check"></i> TRANSAKSI SIMULASI AKADEMIK</span>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-custom h-100 p-3">
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted mb-3" style="height: 180px;">
                                <i class="bi bi-box-seam fs-1 text-primary"></i>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-primary border align-self-start mb-2" style="font-size:0.75rem;">
                                {{ $product->category->name }}
                            </span>
                            <h5 class="fw-bold mb-1 fs-6">{{ $product->name }}</h5>
                            <p class="small text-muted mb-2"><i class="bi bi-shop me-1 text-secondary"></i> {{ $product->merchant->business_name }}</p>
                            
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block" style="font-size:0.75rem;">Harga</small>
                                    <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-ocean btn-sm rounded-pill px-3">
                                    <i class="bi bi-cart-plus me-1"></i> Beli
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">Tidak ada produk yang cocok dengan pencarian Anda.</h5>
                        <a href="{{ route('catalog') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-2">Lihat Semua Produk</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
