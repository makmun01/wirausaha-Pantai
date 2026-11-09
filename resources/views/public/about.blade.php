@extends('layouts.public')

@section('title', 'Tentang Pantai Jumiang - Profil & Fasilitas')

@section('content')
<div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #075985, #0f172a);">
    <div class="container text-center py-3">
        <span class="badge-demo mb-2"><i class="bi bi-info-circle-fill"></i> INFORMASI RESMI BERLABEL DATA DEMO</span>
        <h1 class="display-5 fw-bold mb-2">Profil Destinasi Pantai Jumiang</h1>
        <p class="lead opacity-90 mb-0">Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card card-custom p-4 mb-4">
                <h3 class="fw-bold text-primary mb-3"><i class="bi bi-water me-2"></i>Tentang Kawasan Wisata</h3>
                <p class="text-secondary leading-relaxed mb-3">
                    {{ $destination->description ?? 'Pantai Jumiang merupakan destinasi wisata bahari unggulan di pesisir selatan Pamekasan dengan panorama tebing karang dan hamparan pantai landai.' }}
                </p>
                <div class="row g-3 py-3 border-top border-bottom my-3">
                    <div class="col-sm-4">
                        <div class="small text-muted">Kecamatan</div>
                        <div class="fw-bold">{{ $destination->district ?? 'Pademawu' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small text-muted">Kabupaten</div>
                        <div class="fw-bold">{{ $destination->regency ?? 'Pamekasan' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small text-muted">Desa</div>
                        <div class="fw-bold">{{ $destination->village ?? 'Tanjung' }}</div>
                    </div>
                </div>
            </div>

            <div class="card card-custom p-4 mb-4">
                <h4 class="fw-bold text-primary mb-3"><i class="bi bi-stars me-2"></i>Daftar Spot & Daya Tarik</h4>
                <div class="list-group list-group-flush">
                    @foreach($destination->attractions ?? [] as $attraction)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $attraction->name }}</h6>
                                <span class="badge {{ $attraction->safety_level === 'SAFE' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    Level Keselamatan: {{ $attraction->safety_level }}
                                </span>
                            </div>
                            <p class="text-muted small mb-0">{{ $attraction->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card card-custom p-4">
                <h4 class="fw-bold text-primary mb-3"><i class="bi bi-shield-check me-2"></i>Fasilitas Umum di Lokasi</h4>
                <div class="row g-3">
                    @foreach($destination->facilities ?? [] as $facility)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $facility->name }}</h6>
                                    <span class="badge bg-secondary" style="font-size:0.7rem;">{{ $facility->category }}</span>
                                </div>
                                <span class="badge bg-success">{{ $facility->condition }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold text-dark mb-3">Informasi Kunjungan</h5>
                <div class="p-3 bg-light rounded-3 mb-3">
                    <span class="text-muted small d-block">Jam Buka</span>
                    <span class="fw-bold fs-5 text-primary">{{ $destination->operating_hours ?? '06:00 - 18:00 WIB' }}</span>
                </div>
                <div class="p-3 bg-light rounded-3 mb-3">
                    <span class="text-muted small d-block">Harga Tiket Masuk</span>
                    <span class="fw-bold fs-4 text-warning">Rp {{ number_format($destination->ticket_price_demo ?? 10000, 0, ',', '.') }}</span>
                    <small class="text-danger d-block" style="font-size:0.75rem;">*Data simulasi awal akademik</small>
                </div>
                <a href="{{ route('reservation.form') }}" class="btn btn-ocean w-100 py-2 rounded-pill fw-bold mb-2">
                    <i class="bi bi-ticket-perforated me-1"></i> Pesan Tiket Online
                </a>
                <a href="{{ route('map') }}" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i> Buka Peta Interaktif
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
