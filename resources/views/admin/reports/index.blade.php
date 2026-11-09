@extends('layouts.admin')

@section('title', 'Laporan & Analitik Berkala')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Pusat Laporan & Rekapitulasi Data</h3>
        <p class="text-muted small mb-0">Ekspor laporan penjualan, kas keuangan, dan reservasi wisatawan</p>
    </div>
    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Cetak Dokumen Laporan
    </button>
</div>

<!-- Report Filter Box -->
<div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-4 d-print-none">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold">Jenis Laporan</label>
            <select name="type" class="form-select form-select-sm">
                <option value="transactions" {{ $type === 'transactions' ? 'selected' : '' }}>Transaksi Marketplace</option>
                <option value="financials" {{ $type === 'financials' ? 'selected' : '' }}>Arus Kas Finansial UMKM</option>
                <option value="reservations" {{ $type === 'reservations' ? 'selected' : '' }}>Reservasi Tiket Wisata</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-ocean btn-sm rounded-pill w-100 fw-semibold">
                <i class="bi bi-filter me-1"></i> Tampilkan Rekap
            </button>
        </div>
    </form>
</div>

<!-- Report Content Table -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="text-center pb-3 border-bottom mb-3">
        <h5 class="fw-bold text-dark text-uppercase mb-1">REKAPITULASI LAPORAN {{ strtoupper($type) }}</h5>
        <small class="text-muted">Periode: {{ $startDate }} s/d {{ $endDate }}</small>
    </div>

    @if($type === 'transactions')
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Gerai UMKM</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td>{{ $o->invoice_no }}</td>
                            <td>{{ $o->created_at->format('d/m/Y') }}</td>
                            <td>{{ $o->merchant->business_name }}</td>
                            <td>{{ $o->user->name }}</td>
                            <td class="fw-bold">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ $o->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data transaksi pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif($type === 'financials')
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Gerai UMKM</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($financials as $f)
                        <tr>
                            <td>{{ $f->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $f->merchant->business_name }}</td>
                            <td>{{ $f->category->name }}</td>
                            <td>{{ $f->description }}</td>
                            <td><span class="badge {{ $f->transaction_type === 'INCOME' ? 'bg-success' : 'bg-danger' }}">{{ $f->transaction_type }}</span></td>
                            <td class="fw-bold">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data arus kas pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Booking</th>
                        <th>Tgl Kunjungan</th>
                        <th>Pemesan</th>
                        <th>Pengunjung</th>
                        <th>Paket</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                        <tr>
                            <td>{{ $r->booking_code }}</td>
                            <td>{{ $r->visit_date->format('d/m/Y') }}</td>
                            <td>{{ $r->user->name }}</td>
                            <td>{{ $r->visitor_count }} Orang</td>
                            <td>{{ $r->package_name }}</td>
                            <td class="fw-bold">Rp {{ number_format($r->total_price, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Tidak ada data reservasi pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
