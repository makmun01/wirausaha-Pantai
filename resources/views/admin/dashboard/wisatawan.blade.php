@extends('layouts.admin')

@section('title', 'Panel Wisatawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Aktivitas Saya (Wisatawan)</h3>
        <p class="text-muted small mb-0">Riwayat pesanan oleh-oleh dan reservasi tiket wisata Anda</p>
    </div>
    <a href="{{ route('catalog') }}" class="btn btn-ocean btn-sm rounded-pill px-3">
        <i class="bi bi-cart-plus me-1"></i> Belanja Lagi
    </a>
</div>

<!-- My Orders -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bag-check me-2 text-primary"></i>Riwayat Pesanan Belanja Saya</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Waktu Pesan</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myOrders as $order)
                    <tr>
                        <td><strong class="text-primary">{{ $order->invoice_no }}</strong></td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $order->status === 'PAID' || $order->status === 'COMPLETED' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order.success', $order->invoice_no) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                Lihat Faktur / Bayar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- My Reservations -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-ticket-perforated me-2 text-success"></i>Tiket & Reservasi Kunjungan Saya</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Jumlah Orang</th>
                    <th>Paket Wisata</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myReservations as $res)
                    <tr>
                        <td><strong class="text-success">{{ $res->booking_code }}</strong></td>
                        <td>{{ $res->visit_date->format('d M Y') }}</td>
                        <td>{{ $res->visitor_count }} Orang</td>
                        <td>{{ $res->package_name }}</td>
                        <td>
                            <span class="badge {{ $res->status === 'ATTENDED' ? 'bg-success' : 'bg-primary' }}">
                                {{ $res->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada tiket reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
