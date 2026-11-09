@extends('layouts.public')

@section('title', 'Pemesanan Tiket & Reservasi Wisata - Pantai Jumiang')

@section('content')
<div class="bg-primary text-white py-4" style="background: linear-gradient(135deg, #075985, #0f172a);">
    <div class="container text-center py-2">
        <h1 class="display-6 fw-bold mb-1">Reservasi Tiket Wisata Pantai Jumiang</h1>
        <p class="lead opacity-90 mb-0 small">Rencanakan kunjungan Anda dan rombongan ke kawasan wisata bahari tebing Pantai Jumiang.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-custom p-4 p-md-5">
                <span class="badge-demo align-self-start mb-3"><i class="bi bi-info-circle"></i> FORMULIR RESERVASI [DATA DEMO]</span>
                <h3 class="fw-bold mb-4 brand-font text-primary">Data Pemesanan Kunjungan</h3>

                <form action="{{ route('reservation.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilihan Paket Wisata</label>
                        <select name="package_name" class="form-select" required>
                            <option value="Tiket Masuk Reguler">Tiket Masuk Reguler (Rp 10.000 / orang)</option>
                            <option value="Paket Wisata Keluarga & Tebing">Paket Wisata Keluarga & Tebing (Rp 40.000 / 4 orang)</option>
                            <option value="Paket Rombongan Bus Wisata">Paket Rombongan Bus Wisata (Rp 200.000 / rombongan)</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Tanggal Rencana Kunjungan</label>
                            <input type="date" name="visit_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Jumlah Pengunjung (Orang)</label>
                            <input type="number" name="visitor_count" class="form-control" value="2" min="1" max="500" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-4">
                        <small class="text-muted d-block mb-1"><i class="bi bi-shield-check text-success"></i> Ketentuan Reservasi:</small>
                        <ul class="small text-secondary ps-3 mb-0">
                            <li>Kode booking unik akan diterbitkan otomatis setelah formulir disimpan.</li>
                            <li>Tunjukkan kode booking kepada petugas loket pengelola di pintu masuk untuk validasi check-in.</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-ocean btn-lg w-100 rounded-pill fw-bold shadow">
                        <i class="bi bi-ticket-perforated me-1"></i> Konfirmasi Pemesanan Tiket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
