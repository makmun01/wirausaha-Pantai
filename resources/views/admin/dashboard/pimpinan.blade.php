@extends('layouts.admin')

@section('title', 'Executive Information System (EIS) - Dashboard Pimpinan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Executive Information System (EIS)</h3>
        <p class="text-muted small mb-0">Laporan Strategis Kinerja Ekonomi Wisata & Klaster UMKM Pantai Jumiang</p>
    </div>
    <span class="badge bg-primary px-3 py-2">ANALITIK EKSEKUTIF</span>
</div>

<!-- Macro KPI Metrics -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Total Perputaran Ekonomi</span>
            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($kpi['total_gross_revenue'], 0, ',', '.') }}</span>
            <small class="text-success d-block mt-1"><i class="bi bi-arrow-up-right"></i> Tren Pertumbuhan Positif</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">UMKM Berdaya Digital</span>
            <span class="fs-4 fw-bold text-success">{{ $kpi['total_umkm'] }} Unit Usaha</span>
            <small class="text-muted d-block mt-1">100% Pembukuan Mandiri</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Katalog Produk Lokal</span>
            <span class="fs-4 fw-bold text-info">{{ $kpi['total_products'] }} SKU Aktif</span>
            <small class="text-muted d-block mt-1">Komoditas Lorjuk & Batik</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-stat">
            <span class="text-muted small d-block">Total Kunjungan Wisatawan</span>
            <span class="fs-4 fw-bold text-warning">{{ $kpi['total_visitors'] }} Jiwa</span>
            <small class="text-muted d-block mt-1">Berdasarkan Reservasi Valid</small>
        </div>
    </div>
</div>

<!-- Chart.js Visualizations -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pendapatan & Omzet UMKM (6 Bulan Terakhir)</h5>
            <div style="height: 300px;">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart text-info me-2"></i>Distribusi Kategori Usaha</h5>
            <div style="height: 300px;">
                <canvas id="sectorChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-2"><i class="bi bi-lightbulb text-warning me-2"></i>Rekomendasi Kebijakan Strategis (Decision Support)</h5>
    <ul class="text-secondary small mb-0 ps-3 leading-relaxed">
        <li><strong>Fokus Pembangunan:</strong> Lonjakan pemesanan produk kuliner lorjuk membutuhkan percepatan perbaikan fasilitas gazebo kuliner dan sanitasi kamar bilas.</li>
        <li><strong>Dukungan Pembiayaan:</strong> 100% UMKM mitra telah memiliki pembukuan kas usaha yang terpisah dari kas pribadi, memenuhi syarat kelayakan verifikasi pendanaan UMKM daerah.</li>
    </ul>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Line Chart Tren Omzet
    const ctxTrend = document.getElementById('revenueTrendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: @json($trends),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) { return 'Rp ' + (val/1000).toLocaleString('id-ID') + 'k'; }
                    }
                }
            }
        }
    });

    // 2. Doughnut Chart Sektor Usaha
    const ctxSector = document.getElementById('sectorChart').getContext('2d');
    const sectorData = @json($sectors);
    new Chart(ctxSector, {
        type: 'doughnut',
        data: {
            labels: sectorData.labels,
            datasets: [{
                data: sectorData.data,
                backgroundColor: ['#0284c7', '#16a34a', '#f59e0b', '#8b5cf6', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush
