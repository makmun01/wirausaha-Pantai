@extends('layouts.admin')

@section('title', 'Manajemen Tiket & Reservasi Wisata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Manajemen Reservasi & Validasi Tiket</h3>
        <p class="text-muted small mb-0">Verifikasi kedatangan rombongan wisatawan Pantai Jumiang</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Loket Validasi Kedatangan (Check-In)</h5>
    <form action="{{ route('admin.reservations.check_in') }}" method="POST" class="row g-3 align-items-end">
        @csrf
        <div class="col-md-8">
            <label class="form-label small fw-bold">Scan atau Masukkan Kode Booking Wisatawan</label>
            <input type="text" name="booking_code" class="form-control" placeholder="Contoh: RSV-JMI-20261102-001" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-ocean w-100 py-2 rounded-pill fw-bold">
                <i class="bi bi-check-circle me-1"></i> Validasi Check-In
            </button>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3">Daftar Seluruh Reservasi</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Pemesan</th>
                    <th>Tgl Kunjungan</th>
                    <th>Jumlah</th>
                    <th>Paket Wisata</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Waktu Check-In</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                    <tr>
                        <td><strong class="text-primary">{{ $res->booking_code }}</strong></td>
                        <td>{{ $res->user->name }}</td>
                        <td>{{ $res->visit_date->format('d/m/Y') }}</td>
                        <td>{{ $res->visitor_count }} Orang</td>
                        <td>{{ $res->package_name }}</td>
                        <td class="fw-bold">Rp {{ number_format($res->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $res->status === 'ATTENDED' ? 'bg-success' : ($res->status === 'CONFIRMED' ? 'bg-primary' : 'bg-warning text-dark') }}">
                                {{ $res->status }}
                            </span>
                        </td>
                        <td>{{ $res->check_in_time ? $res->check_in_time->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada data reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
