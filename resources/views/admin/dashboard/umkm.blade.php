@extends('layouts.admin')

@section('title', 'Dashboard Toko UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">{{ $merchant->business_name }}</h3>
        <p class="text-muted small mb-0">Pemilik: <strong>{{ $merchant->owner_name }}</strong> | Kategori: {{ $merchant->category }}</p>
    </div>
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-ocean btn-sm rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

<!-- Financial & Operations Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Saldo Kas Usaha (Terpisah)</span>
                <span class="fs-4 fw-bold text-success">Rp {{ number_format($cashBalance, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Katalog Produk Aktif</span>
                <span class="fs-4 fw-bold text-dark">{{ $productsCount }} Produk</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Peringatan Stok Menipis</span>
                <span class="fs-4 fw-bold text-danger">{{ count($lowStockProducts) }} Produk</span>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert Banner if any -->
@if(count($lowStockProducts) > 0)
    <div class="alert alert-warning border-warning rounded-3 mb-4 p-3 d-flex align-items-center gap-3 shadow-sm">
        <i class="bi bi-bell-fill fs-3 text-warning"></i>
        <div>
            <strong class="d-block text-dark">Perhatian: Ada {{ count($lowStockProducts) }} produk yang berada di bawah batas minimum stok!</strong>
            <small class="text-muted">Segera lakukan pencatatan stok masuk (restock) agar produk tidak kehabisan di marketplace.</small>
        </div>
    </div>
@endif

<!-- Recent Merchant Orders -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cart3 me-2 text-primary"></i>Pesanan Masuk Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">Kelola Semua Pesanan</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Waktu</th>
                    <th>Opsi Pengantaran</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($merchantOrders as $order)
                    <tr>
                        <td><strong class="text-primary">{{ $order->invoice_no }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $order->delivery_option }}</span></td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $order->status === 'PAID' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
