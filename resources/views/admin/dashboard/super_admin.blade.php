@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Ringkasan Sistem (Super Admin)</h3>
        <p class="text-muted small mb-0">Kontrol penuh ekosistem Jumiang Smart Tourism & UMKM</p>
    </div>
    <div>
        <a href="{{ route('admin.umkm.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">
            <i class="bi bi-shop me-1"></i> Verifikasi UMKM
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <span class="text-muted small d-block">UMKM Terverifikasi</span>
                <span class="fs-4 fw-bold text-dark">{{ $kpi['total_umkm'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Total Produk Aktif</span>
                <span class="fs-4 fw-bold text-dark">{{ $kpi['total_products'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-cart-check"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Total Transaksi</span>
                <span class="fs-4 fw-bold text-dark">{{ $kpi['total_orders'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Perputaran Omzet</span>
                <span class="fs-5 fw-bold text-dark">Rp {{ number_format($kpi['total_gross_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Tables: Recent Orders & Recent UMKM -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-1 text-primary"></i> Transaksi Marketplace Terbaru</h6>
                <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">Semua Pesanan</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Merchant</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $order->invoice_no }}</span></td>
                                <td>{{ $order->merchant->business_name }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'PAID' || $order->status === 'COMPLETED' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada pesanan terbaru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shop me-1 text-success"></i> Gerai UMKM Terdaftar</h6>
                <a href="{{ route('admin.umkm.index') }}" class="small text-decoration-none">Lihat Semua</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentUmkm as $merchant)
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold d-block text-dark">{{ $merchant->business_name }}</span>
                            <small class="text-muted">{{ $merchant->owner_name }} ({{ $merchant->category }})</small>
                        </div>
                        <span class="badge {{ $merchant->verification_status === 'VERIFIED' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $merchant->verification_status }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small">Belum ada UMKM terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
