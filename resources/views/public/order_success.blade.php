@extends('layouts.public')

@section('title', 'Faktur Pesanan #' . $order->invoice_no)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-custom p-4 p-md-5 text-center">
                <div class="mb-3">
                    @if($order->status === 'PAID' || $order->status === 'COMPLETED')
                        <i class="bi bi-check-circle-fill display-3 text-success"></i>
                        <h3 class="fw-bold text-success mt-2">Pembayaran Berhasil!</h3>
                        <p class="text-muted">Pesanan Anda telah lunas dan sedang dipersiapkan oleh gerai UMKM.</p>
                    @else
                        <i class="bi bi-hourglass-split display-3 text-warning"></i>
                        <h3 class="fw-bold text-dark mt-2">Menunggu Pembayaran Simulasi</h3>
                        <p class="text-muted">Silakan selesaikan simulasi transaksi digital di bawah ini.</p>
                    @endif
                </div>

                <div class="p-3 bg-light rounded-3 text-start mb-4">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Nomor Invoice</span>
                        <strong class="text-primary">{{ $order->invoice_no }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Gerai UMKM</span>
                        <strong>{{ $order->merchant->business_name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Status Pesanan</span>
                        <span class="badge {{ $order->status === 'PAID' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Metode Pembayaran</span>
                        <span class="badge bg-secondary">{{ $order->payment->payment_method ?? 'SIMULASI' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 fs-5">
                        <span class="fw-bold">Total Nilai Pesanan</span>
                        <span class="fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->status === 'PENDING')
                    <!-- Simulator Payment Box -->
                    <div class="p-4 border border-warning rounded-3 bg-warning bg-opacity-10 mb-4">
                        <span class="badge bg-warning text-dark mb-2"><i class="bi bi-cpu"></i> SIMULATOR DIGITAL PAYMENT GATEWAY</span>
                        <h6 class="fw-bold text-dark">Kode Referensi Simulasi:</h6>
                        <code class="fs-5 fw-bold d-block my-2 text-dark">{{ $order->payment->simulation_ref_no ?? 'SIM-REF-992182' }}</code>
                        
                        <p class="small text-muted mb-3">Klik tombol di bawah ini untuk mensimulasikan respons sukses dari penyedia layanan pembayaran digital:</p>
                        
                        <form action="{{ route('payment.simulate', $order->invoice_no) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow">
                                <i class="bi bi-wallet2 me-2"></i> Konfirmasi Bayar (Simulasi Lunas)
                            </button>
                        </form>
                    </div>
                @endif

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('catalog') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-shop me-1"></i> Kembali Belanja
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-ocean rounded-pill px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Lihat di Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
