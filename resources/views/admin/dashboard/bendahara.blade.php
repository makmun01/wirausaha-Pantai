@extends('layouts.admin')

@section('title', 'Dashboard Bendahara Kawasan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Panel Bendahara Keuangan Kawasan</h3>
        <p class="text-muted small mb-0">Verifikasi arus kas, rekonsiliasi transaksi gerai, dan pencatatan kas bersama</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Estimasi Omzet UMKM Terkumpul</span>
                <span class="fs-4 fw-bold text-success">Rp {{ number_format($kpi['total_gross_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Total Mutasi Transaksi</span>
                <span class="fs-4 fw-bold text-dark">{{ count($recentTransactions) }} Transaksi</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card-stat d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Status Rekonsiliasi</span>
                <span class="fs-5 fw-bold text-dark">Terverifikasi Akurat</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Cash Transactions Table -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-check me-2 text-primary"></i>Jurnal Mutasi Keuangan Terkini</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Gerai UMKM</th>
                    <th>Akun Rekening</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                        <td><strong>{{ $t->merchant->business_name }}</strong></td>
                        <td>{{ $t->account->account_name }} ({{ $t->account->account_type }})</td>
                        <td>{{ $t->category->name }}</td>
                        <td>
                            <span class="badge {{ $t->transaction_type === 'INCOME' ? 'bg-success' : 'bg-danger' }}">
                                {{ $t->transaction_type }}
                            </span>
                        </td>
                        <td class="fw-bold {{ $t->transaction_type === 'INCOME' ? 'text-success' : 'text-danger' }}">
                            {{ $t->transaction_type === 'INCOME' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada transaksi keuangan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
