@extends('layouts.public')

@section('title', 'Daftar Akun Baru - Jumiang Smart Tourism')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus text-primary display-4"></i>
                    <h3 class="fw-bold brand-font text-dark mt-2">Daftar Akun Wisatawan</h3>
                    <p class="text-muted small">Buat akun untuk melakukan pemesanan tiket wisata dan belanja oleh-oleh khas</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="081234567890" value="{{ old('phone_number') }}">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary">Kata Sandi</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ocean w-100 py-2 rounded-pill fw-bold shadow mb-3">
                        <i class="bi bi-check2-circle me-1"></i> Selesaikan Pendaftaran
                    </button>
                </form>

                <div class="text-center mt-3 small text-muted">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di Sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
