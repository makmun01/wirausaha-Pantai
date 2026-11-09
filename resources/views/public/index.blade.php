@extends('layouts.public')

@section('title', 'Beranda - Jelajahi Pantai Jumiang & UMKM Lokal')

@section('content')
<!-- Hero Section -->
<section class="hero-banner text-center text-lg-start">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="badge-demo mb-3"><i class="bi bi-geo-alt-fill"></i> PADEMAWU, KABUPATEN PAMEKASAN, MADURA</div>
                <h1 class="display-4 fw-extrabold text-white mb-3 leading-tight">
                    Pesona Bahari Tebing & <br><span class="text-info">Ekosistem UMKM Jumiang</span>
                </h1>
                <p class="lead text-light mb-4 opacity-90">
                    Sistem terpadu informasi pariwisata bahari, peta geospasial interaktif, etalase produk olahan laut khas lorjuk, dan pembukuan keuangan mandiri pedagang lokal Desa Tanjung.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('map') }}" class="btn btn-ocean btn-lg px-4 rounded-pill">
                        <i class="bi bi-map-fill me-2"></i> Buka Peta Wisata
                    </a>
                    <a href="{{ route('catalog') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                        <i class="bi bi-shop me-2"></i> Belanja Produk UMKM
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-25 rounded-4 p-4 text-white shadow-lg">
                    <h5 class="fw-bold text-info mb-3"><i class="bi bi-compass me-2"></i>Informasi Cepat Wisata</h5>
                    <div class="d-flex justify-content-between py-2 border-bottom border-light border-opacity-25">
                        <span class="text-light">Lokasi</span>
                        <span class="fw-semibold">Desa Tanjung, Pademawu</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom border-light border-opacity-25">
                        <span class="text-light">Jam Operasional</span>
                        <span class="fw-semibold">{{ $destination->operating_hours ?? '06:00 - 18:00 WIB' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom border-light border-opacity-25">
                        <span class="text-light">Tarif Retribusi [DEMO]</span>
                        <span class="fw-bold text-warning">Rp {{ number_format($destination->ticket_price_demo ?? 10000, 0, ',', '.') }} / orang</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-light">Komoditas Utama</span>
                        <span class="fw-semibold">Olahan Lorjuk & Batik Bahari</span>
                    </div>
                    <a href="{{ route('reservation.form') }}" class="btn btn-info text-dark fw-bold rounded-pill w-100 mt-3">
                        <i class="bi bi-ticket-perforated me-1"></i> Pesan Tiket Wisata Online
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats Bar -->
<section class="py-4 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="text-secondary small fw-bold">TOTAL DAYA TARIK</div>
                <div class="fs-2 fw-extrabold text-primary">4 Spot</div>
                <small class="text-muted">Tebing, Pantai & Sunset</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-secondary small fw-bold">MITRA UMKM TERDAFTAR</div>
                <div class="fs-2 fw-extrabold text-info">{{ $umkmCount }} Gerai</div>
                <small class="text-muted">Olahan Laut & Batik</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-secondary small fw-bold">TITIK LOKASI PETA</div>
                <div class="fs-2 fw-extrabold text-success">{{ $markersCount }} Marker</div>
                <small class="text-muted">Koordinat Presisi WGS84</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-secondary small fw-bold">SISTEM KEUANGAN</div>
                <div class="fs-2 fw-extrabold text-warning">Terpisah</div>
                <small class="text-muted">Arus Kas Usaha vs Pribadi</small>
            </div>
        </div>
    </div>
</section>

<!-- Featured Attractions -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1 rounded-pill mb-2">DAYA TARIK UNGGULAN</span>
                <h2 class="fw-bold mb-0">Spot Ikonik Pantai Jumiang</h2>
            </div>
            <a href="{{ route('about') }}" class="btn btn-outline-primary rounded-pill btn-sm fw-semibold">Lihat Semua Info <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @forelse($destination->attractions ?? [] as $attraction)
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom h-100 overflow-hidden">
                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-secondary" style="height: 180px;">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                        <div class="card-body">
                            <span class="badge {{ $attraction->safety_level === 'SAFE' ? 'bg-success' : 'bg-warning text-dark' }} mb-2" style="font-size:0.7rem;">
                                {{ $attraction->safety_level }}
                            </span>
                            <h5 class="card-title fw-bold">{{ $attraction->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($attraction->description, 90) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted text-center py-4">Data atraksi sedang dipersiapkan.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured UMKM Products -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill mb-2">ETALASE DIGITAL UMKM</span>
                <h2 class="fw-bold mb-0">Oleh-Oleh & Kerajinan Khas</h2>
            </div>
            <a href="{{ route('catalog') }}" class="btn btn-outline-success rounded-pill btn-sm fw-semibold">Katalog Lengkap <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 p-3">
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted mb-3" style="height: 190px;">
                            <i class="bi bi-bag-check fs-1 text-primary"></i>
                        </div>
                        <span class="badge bg-light text-primary border align-self-start mb-2" style="font-size:0.75rem;">
                            {{ $product->category->name }}
                        </span>
                        <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                        <p class="small text-muted mb-2"><i class="bi bi-shop me-1 text-secondary"></i> {{ $product->merchant->business_name }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <div>
                                <small class="text-muted d-block" style="font-size:0.75rem;">Harga</small>
                                <span class="fs-5 fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-ocean btn-sm rounded-pill px-3">
                                <i class="bi bi-cart-plus me-1"></i> Beli
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted text-center py-4">Belum ada produk aktif.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- GIS Map Teaser -->
<section class="py-5 text-center bg-light">
    <div class="container py-4">
        <i class="bi bi-map text-primary display-3 mb-3 d-block"></i>
        <h2 class="fw-bold mb-3">Eksplorasi Spasial Kawasan Wisata</h2>
        <p class="text-muted max-w-600 mx-auto mb-4" style="max-width: 650px;">
            Temukan lokasi tepat spot swafoto tebing karang, gazebo beristirahat, fasilitas ibadah, toilet umum, area parkir kendaraan, serta titik gerai pedagang UMKM menggunakan peta digital interaktif Leaflet.js.
        </p>
        <a href="{{ route('map') }}" class="btn btn-ocean btn-lg px-5 rounded-pill shadow">
            <i class="bi bi-geo-alt-fill me-2"></i> Buka Peta Geospasial
        </a>
    </div>
</section>
@endsection
