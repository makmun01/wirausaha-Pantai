<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jumiang Smart Tourism & UMKM') - Pantai Jumiang Pamekasan</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Custom Maritime Theme Styling -->
    <style>
        :root {
            --ocean-blue: #0284c7;
            --ocean-dark: #0f172a;
            --deep-sea: #075985;
            --coastal-sand: #f8fafc;
            --seafoam-accent: #06b6d4;
            --coral-orange: #f97316;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--coastal-sand);
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .navbar-marine {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-link {
            font-weight: 500;
            color: #e2e8f0 !important;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--seafoam-accent) !important;
        }

        .hero-banner {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.88), rgba(7, 89, 133, 0.85)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: white;
            padding: 90px 0;
            border-bottom: 4px solid var(--seafoam-accent);
        }

        .btn-ocean {
            background: linear-gradient(135deg, #0284c7, #075985);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-ocean:hover {
            background: linear-gradient(135deg, #0369a1, #0c4a6e);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
        }

        .badge-demo {
            background-color: #fef08a;
            color: #854d0e;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid #fde047;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }

        footer {
            background-color: var(--ocean-dark);
            color: #94a3b8;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Top Alert Demo Notification -->
    <div class="bg-warning text-dark py-1 text-center small fw-semibold">
        <i class="bi bi-info-circle-fill me-1"></i> [DATA DEMO / SIMULASI] Sistem ini dikembangkan sesuai RPS MANPRO untuk memodelkan digitalisasi UMKM dan Wisata Bahari Pantai Jumiang, Pamekasan.
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-marine sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 brand-font fw-bold fs-4" href="{{ route('home') }}">
                <i class="bi bi-water text-info fs-3"></i>
                <span>JUMIANG <span class="text-info">SMART TOURISM</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="bi bi-compass me-1"></i> Pantai Jumiang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">
                            <i class="bi bi-geo-alt me-1"></i> Peta Wisata (GIS)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">
                            <i class="bi bi-shop me-1"></i> Katalog UMKM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservation.*') ? 'active' : '' }}" href="{{ route('reservation.form') }}">
                            <i class="bi bi-ticket-perforated me-1"></i> Reservasi Tiket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="font-size:0.65rem;">
                                2
                            </span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        @auth
                            <div class="dropdown">
                                <a class="btn btn-outline-info btn-sm dropdown-toggle rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Panel Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a class="btn btn-info btn-sm rounded-pill px-3 fw-semibold text-dark" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk / Demo
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Body Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <h5 class="text-white brand-font fw-bold"><i class="bi bi-water text-info me-2"></i>PANTAI JUMIANG</h5>
                    <p class="small text-secondary">Sistem Informasi Manajemen Wisata Bahari & Marketplace UMKM Terintegrasi. Mengembangkan potensi ekonomi masyarakat Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura.</p>
                    <span class="badge-demo"><i class="bi bi-shield-check"></i> PROYEK AKADEMIK MANPRO 16 PERTEMUAN</span>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="text-white fw-bold mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-secondary text-decoration-none hover-info">Profil Destinasi</a></li>
                        <li class="mb-2"><a href="{{ route('map') }}" class="text-secondary text-decoration-none hover-info">Peta Geospasial GIS</a></li>
                        <li class="mb-2"><a href="{{ route('catalog') }}" class="text-secondary text-decoration-none hover-info">Katalog Produk Lokal</a></li>
                        <li class="mb-2"><a href="{{ route('reservation.form') }}" class="text-secondary text-decoration-none hover-info">Tiket & Paket Wisata</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-6">
                    <h6 class="text-white fw-bold mb-3">Informasi Lokasi</h6>
                    <p class="small text-secondary mb-1"><i class="bi bi-geo-alt-fill text-info me-2"></i>Desa Tanjung, Kec. Pademawu, Kab. Pamekasan, Jawa Timur</p>
                    <p class="small text-secondary mb-1"><i class="bi bi-clock-fill text-info me-2"></i>Operasional: 06:00 - 18:00 WIB</p>
                    <p class="small text-secondary"><i class="bi bi-envelope-fill text-info me-2"></i>pokdarwis.jumiang@pamekasan.go.id</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center small text-secondary">
                <p class="mb-0">&copy; 2026 Jumiang Smart Tourism & UMKM. Dilindungi Hak Cipta.</p>
                <p class="mb-0">Ditenagai oleh Laravel & Leaflet.js</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet.js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
