@extends('layouts.admin')

@section('title', 'Dashboard Pengelola Wisata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Panel Pengelola Wisata Pantai Jumiang</h3>
        <p class="text-muted small mb-0">Pengelolaan tiket masuk, fasilitas wisata, dan verifikasi kedatangan wisatawan</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Total Wisatawan Terlayani</span>
                <span class="fs-4 fw-bold text-dark">{{ $totalVisitors }} Orang</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-ticket-perforated-fill"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Reservasi Menunggu Kedatangan</span>
                <span class="fs-4 fw-bold text-dark">{{ $activeReservations }} Booking</span>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="card card-custom p-3 bg-white">
            <h6 class="fw-bold small mb-2 text-primary"><i class="bi bi-qr-code-scan me-1"></i> Validasi Cepat Check-In</h6>
            <form action="{{ route('admin.reservations.check_in') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="booking_code" class="form-control form-control-sm" placeholder="Contoh: RSV-JMI-..." required>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Verifikasi</button>
            </form>
        </div>
    </div>
</div>

<!-- Recent Reservations -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Reservasi Terbaru</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Pemesan</th>
                    <th>Tgl Kunjungan</th>
                    <th>Jumlah Orang</th>
                    <th>Paket Wisata</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReservations as $r)
                    <tr>
                        <td><strong class="text-primary">{{ $r->booking_code }}</strong></td>
                        <td>{{ $r->user->name }}</td>
                        <td>{{ $r->visit_date->format('d M Y') }}</td>
                        <td>{{ $r->visitor_count }} Orang</td>
                        <td>{{ $r->package_name }}</td>
                        <td>
                            <span class="badge {{ $r->status === 'ATTENDED' ? 'bg-success' : ($r->status === 'CONFIRMED' ? 'bg-primary' : 'bg-warning text-dark') }}">
                                {{ $r->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
