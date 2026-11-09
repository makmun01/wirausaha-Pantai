@extends('layouts.public')

@section('title', 'Checkout Pesanan - Jumiang Smart Tourism')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4">
                <h3 class="fw-bold mb-4 brand-font text-primary"><i class="bi bi-credit-card me-2"></i>Checkout Pesanan</h3>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="umkm_merchant_id" value="1">
                    <input type="hidden" name="items[0][product_id]" value="1">
                    <input type="hidden" name="items[0][quantity]" value="2">
                    <input type="hidden" name="items[1][product_id]" value="2">
                    <input type="hidden" name="items[1][quantity]" value="1">

                    <!-- Delivery Option -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Opsi Penyerahan Produk</label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-check p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="PICKUP" checked>
                                    <label class="form-check-label fw-semibold" for="pickup">
                                        <i class="bi bi-shop me-1 text-primary"></i> Ambil di Gerai (Pick-up)
                                        <small class="d-block text-muted">Ambil langsung di sentra kuliner Jumiang</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="delivery_option" id="delivery" value="DELIVERY">
                                    <label class="form-check-label fw-semibold" for="delivery">
                                        <i class="bi bi-bicycle me-1 text-success"></i> Antar ke Spot / Homestay
                                        <small class="d-block text-muted">Diantar ke area gazebo atau penginapan</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Simulation Selector -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran (Simulasi Digital Gateway)</label>
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <span class="badge bg-warning text-dark mb-2"><i class="bi bi-info-circle"></i> DATA DEMO / SIMULASI</span>
                            <p class="small text-muted mb-0">Pembayaran menggunakan gateway simulasi tanpa memotong saldo bank nyata, sesuai pedoman akademik RPS MANPRO.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div class="form-check p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="qris" value="SIMULATION_QRIS" checked>
                                    <label class="form-check-label fw-semibold" for="qris">
                                        <i class="bi bi-qr-code me-1 text-primary"></i> QRIS Simulasi
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="va" value="SIMULATION_VA">
                                    <label class="form-check-label fw-semibold" for="va">
                                        <i class="bi bi-bank me-1 text-info"></i> Virtual Account
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash" value="CASH_ON_LOCATION">
                                    <label class="form-check-label fw-semibold" for="cash">
                                        <i class="bi bi-cash me-1 text-success"></i> Tunai di Lokasi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery & Special Notes -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Catatan Pengantaran / Lokasi Anda</label>
                        <input type="text" name="delivery_notes" class="form-control" placeholder="Contoh: Gazebo No. 4 atau Kamar Homestay No. 2">
                    </div>

                    <!-- Items Summary -->
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold mb-2">Item yang Dipesan:</h6>
                        <div class="d-flex justify-content-between small py-1">
                            <span>2x Rengginang Lorjuk Super</span>
                            <span class="fw-semibold">Rp 70.000</span>
                        </div>
                        <div class="d-flex justify-content-between small py-1 border-bottom">
                            <span>1x Keripik Ikan Teri Krispi</span>
                            <span class="fw-semibold">Rp 20.000</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold pt-2 fs-5 text-primary">
                            <span>Total Pembayaran:</span>
                            <span>Rp 90.000</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ocean btn-lg w-100 rounded-pill fw-bold shadow">
                        <i class="bi bi-check2-circle me-1"></i> Buat Pesanan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
