@extends('layouts.admin')

@section('title', 'Executive Information System (EIS) Lengkap')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Executive Information System (EIS) Lengkap</h3>
        <p class="text-muted small mb-0">Platform Intelijen Bisnis & Pengambilan Keputusan Wisata Bahari Pantai Jumiang</p>
    </div>
    <span class="badge bg-primary px-3 py-2">MODUL EIS MOD-12</span>
</div>

<!-- Macro Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Total Perputaran Omzet</span>
            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($kpi['total_gross_revenue'], 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">UMKM Berdaya</span>
            <span class="fs-4 fw-bold text-success">{{ $kpi['total_umkm'] }} Merchant</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Total Transaksi</span>
            <span class="fs-4 fw-bold text-info">{{ $kpi['total_orders'] }} Order</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Wisatawan Terdata</span>
            <span class="fs-4 fw-bold text-warning">{{ $kpi['total_visitors'] }} Orang</span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Tren Penjualan Produk UMKM Kawasan</h5>
            <div style="height: 320px;">
                <canvas id="eisTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart text-info me-2"></i>Proporsi Sektor UMKM</h5>
            <div style="height: 320px;">
                <canvas id="eisSectorChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Merchant Revenue Matrix -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-trophy text-warning me-2"></i>Kinerja Omzet Tiap Gerai UMKM</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Gerai UMKM</th>
                    <th>Nama Pemilik</th>
                    <th>Kategori</th>
                    <th>Jumlah SKU</th>
                    <th>Total Omzet Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($merchants as $m)
                    <tr>
                        <td><strong class="text-primary">{{ $m->business_name }}</strong></td>
                        <td>{{ $m->owner_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $m->category }}</span></td>
                        <td>{{ $m->products_count }} Produk</td>
                        <td class="fw-bold text-success">Rp {{ number_format($m->orders_sum_total_amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    new Chart(document.getElementById('eisTrendChart').getContext('2d'), {
        type: 'line',
        data: @json($trends),
        options: { responsive: true, maintainAspectRatio: false }
    });

    const sectorData = @json($sectors);
    new Chart(document.getElementById('eisSectorChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: sectorData.labels,
            datasets: [{
                data: sectorData.data,
                backgroundColor: ['#0284c7', '#16a34a', '#f59e0b', '#8b5cf6', '#ec4899']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endpush
