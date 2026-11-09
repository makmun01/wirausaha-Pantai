<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Jumiang Smart Tourism</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --accent-blue: #0284c7;
            --bg-light: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: #94a3b8;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .brand {
            padding: 22px 20px;
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-nav {
            padding: 15px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            margin-bottom: 4px;
            transition: all 0.2s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background-color: var(--sidebar-hover);
        }

        .sidebar-link.active {
            background-color: var(--accent-blue);
            color: white;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 28px;
        }

        .content-body {
            padding: 28px;
            flex: 1;
        }

        .card-stat {
            background: white;
            border-radius: 12px;
            padding: 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand d-flex align-items-center gap-2">
            <i class="bi bi-water text-info fs-3"></i>
            <div>
                <span class="d-block text-white brand-font leading-tight">JUMIANG ERP</span>
                <small class="text-secondary" style="font-size:0.7rem;">Smart Tourism & UMKM</small>
            </div>
        </div>
        <div class="sidebar-nav">
            <div class="text-uppercase small fw-bold px-3 py-2 text-secondary" style="font-size:0.7rem;">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if(Auth::user()->hasRole(['pelaku_umkm', 'super_admin']))
                <div class="text-uppercase small fw-bold px-3 pt-3 pb-2 text-secondary" style="font-size:0.7rem;">Operasional UMKM</div>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk Saya
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-archive"></i> Inventori & Stok
                </a>
                <a href="{{ route('admin.financial.index') }}" class="sidebar-link {{ request()->routeIs('admin.financial.index') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i> Buku Kas Mandiri
                </a>
                <a href="{{ route('admin.financial.profit_loss') }}" class="sidebar-link {{ request()->routeIs('admin.financial.profit_loss') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> Laporan Laba Rugi
                </a>
            @endif

            @if(Auth::user()->hasRole(['pelaku_umkm', 'super_admin', 'pengelola_wisata']))
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i> Pesanan Masuk
                </a>
            @endif

            @if(Auth::user()->hasRole(['pengelola_wisata', 'super_admin']))
                <div class="text-uppercase small fw-bold px-3 pt-3 pb-2 text-secondary" style="font-size:0.7rem;">Pengelolaan Wisata</div>
                <a href="{{ route('admin.reservations.index') }}" class="sidebar-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i> Reservasi & Check-in
                </a>
                <a href="{{ route('map') }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-geo-alt"></i> Peta GIS (Publik)
                </a>
            @endif

            @if(Auth::user()->hasRole(['super_admin']))
                <div class="text-uppercase small fw-bold px-3 pt-3 pb-2 text-secondary" style="font-size:0.7rem;">Tata Kelola Sistem</div>
                <a href="{{ route('admin.umkm.index') }}" class="sidebar-link {{ request()->routeIs('admin.umkm.*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i> Verifikasi Mitra UMKM
                </a>
                <a href="{{ route('admin.partnerships.index') }}" class="sidebar-link {{ request()->routeIs('admin.partnerships.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase"></i> Kemitraan & MoU
                </a>
                <a href="{{ route('admin.audit.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Audit Trail Log
                </a>
            @endif

            @if(Auth::user()->hasRole(['pimpinan', 'super_admin', 'bendahara']))
                <div class="text-uppercase small fw-bold px-3 pt-3 pb-2 text-secondary" style="font-size:0.7rem;">Eksekutif & Laporan</div>
                <a href="{{ route('admin.eis.index') }}" class="sidebar-link {{ request()->routeIs('admin.eis.*') ? 'active' : '' }}">
                    <i class="bi bi-pie-chart"></i> EIS & Analitik Makro
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Ekspor Laporan
                </a>
            @endif

            <hr class="border-secondary my-3">
            <a href="{{ route('home') }}" class="sidebar-link text-info">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Web Publik
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white text-uppercase px-3 py-2 fw-bold" style="font-size:0.75rem;">
                    <i class="bi bi-shield-lock-fill me-1"></i> ROLE: {{ Auth::user()->roles->first()?->display_name ?? 'Pengguna' }}
                </span>
                <span class="badge bg-warning text-dark px-2 py-1 small">
                    <i class="bi bi-info-circle"></i> DATA DEMO / SIMULASI
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="d-block fw-bold text-dark leading-tight">{{ Auth::user()->name }}</span>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-2 shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-4 text-primary"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><span class="dropdown-header">Akun Anda</span></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i> Portal Publik</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Main Body -->
        <div class="content-body">
            @yield('content')
        </div>

        <!-- Admin Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            &copy; 2026 Jumiang Smart Tourism & UMKM Management System. Standar Kualitas RPS MANPRO & ISO 29119.
        </footer>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
