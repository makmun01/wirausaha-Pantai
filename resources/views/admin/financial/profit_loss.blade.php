@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi Sederhana UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Laporan Laba Rugi Sederhana</h3>
        <p class="text-muted small mb-0">{{ $merchant->business_name ?? 'UMKM Pantai Jumiang' }} | Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan (PDF)
        </button>
        <a href="{{ route('admin.financial.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Buku Kas
        </a>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-4 d-print-none">
    <form action="{{ route('admin.financial.profit_loss') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-sm-4">
            <label class="form-label small fw-bold">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
        </div>
        <div class="col-sm-4">
            <label class="form-label small fw-bold">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-ocean btn-sm rounded-pill w-100 fw-semibold">
                <i class="bi bi-filter me-1"></i> Terapkan Filter Periode
            </button>
        </div>
    </form>
</div>

<!-- Financial Summary Statement -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="text-center pb-3 border-bottom mb-4">
        <h4 class="fw-bold text-dark mb-1">{{ $merchant->business_name ?? 'Sentra Olahan UMKM' }}</h4>
        <h6 class="text-secondary mb-0">LAPORAN LABA / RUGI OPERASIONAL USAHA</h6>
        <small class="text-muted">Periode: {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}</small>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                <span class="small text-success fw-bold d-block">TOTAL PEMASUKAN (REVENUE)</span>
                <span class="fs-4 fw-bold text-success">Rp {{ number_format($report['total_income'], 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-danger bg-opacity-10 rounded-3 border border-danger border-opacity-25">
                <span class="small text-danger fw-bold d-block">TOTAL PENGELUARAN (EXPENSES)</span>
                <span class="fs-4 fw-bold text-danger">Rp {{ number_format($report['total_expense'], 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 {{ $report['is_profit'] ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 rounded-3 border {{ $report['is_profit'] ? 'border-primary' : 'border-danger' }} border-opacity-25">
                <span class="small {{ $report['is_profit'] ? 'text-primary' : 'text-danger' }} fw-bold d-block">
                    {{ $report['is_profit'] ? 'LABA BERSIH (NET PROFIT)' : 'RUGI BERSIH (NET LOSS)' }}
                </span>
                <span class="fs-4 fw-bold {{ $report['is_profit'] ? 'text-primary' : 'text-danger' }}">
                    Rp {{ number_format($report['net_profit'], 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-dark mb-2">Rincian Transaksi Pendukung:</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-end">Pemasukan</th>
                    <th class="text-end">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['transactions'] as $tx)
                    <tr>
                        <td>{{ $tx->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $tx->category->name }}</td>
                        <td>{{ $tx->description }}</td>
                        <td class="text-end text-success fw-semibold">
                            {{ $tx->transaction_type === 'INCOME' ? 'Rp ' . number_format($tx->amount, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-end text-danger fw-semibold">
                            {{ $tx->transaction_type === 'EXPENSE' ? 'Rp ' . number_format($tx->amount, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
