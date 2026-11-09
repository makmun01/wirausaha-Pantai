@extends('layouts.public')

@section('title', 'Masuk Sistem - Jumiang Smart Tourism')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-water text-primary display-4"></i>
                    <h3 class="fw-bold brand-font text-dark mt-2">Masuk ke Sistem</h3>
                    <p class="text-muted small">Akses multi-role ekosistem Jumiang Smart Tourism & UMKM</p>
                </div>

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="emailInput" class="form-control" placeholder="nama@email.com" value="{{ old('email', 'admin@jumiang.test') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Kata Sandi (Password)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control" value="Password123!" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ocean w-100 py-2 rounded-pill fw-bold shadow mb-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Demo Quick Fill Box (Akademik Helper) -->
                <div class="p-3 bg-light rounded-3 border">
                    <span class="badge bg-warning text-dark mb-2 small"><i class="bi bi-lightning-charge-fill"></i> AKUN DEMO AKADEMIK (KLIK UNTUK ISI OTOMATIS)</span>
                    <div class="d-grid gap-1 small">
                        <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="fillDemo('admin@jumiang.test', 'Password123!')">
                            <strong>1. Super Admin:</strong> <code>admin@jumiang.test</code>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm text-start text-dark" onclick="fillDemo('pengelola@jumiang.test', 'Password123!')">
                            <strong>2. Pengelola Wisata:</strong> <code>pengelola@jumiang.test</code>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm text-start" onclick="fillDemo('umkm.maryam@jumiang.test', 'Password123!')">
                            <strong>3. Pelaku UMKM:</strong> <code>umkm.maryam@jumiang.test</code>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm text-start" onclick="fillDemo('bendahara@jumiang.test', 'Password123!')">
                            <strong>4. Bendahara:</strong> <code>bendahara@jumiang.test</code>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm text-start" onclick="fillDemo('pimpinan@jumiang.test', 'Password123!')">
                            <strong>5. Pimpinan / EIS:</strong> <code>pimpinan@jumiang.test</code>
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm text-start text-dark" onclick="fillDemo('wisatawan@jumiang.test', 'Password123!')">
                            <strong>6. Wisatawan:</strong> <code>wisatawan@jumiang.test</code>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-4 small text-muted">
                    Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Wisatawan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemo(email, pass) {
    document.getElementById('emailInput').value = email;
    document.getElementById('passwordInput').value = pass;
}
</script>
@endsection
