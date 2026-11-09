<?php

/**
 * JUMIANG SMART TOURISM & UMKM
 * Main Web Entry Point (Laravel Bootstrap with Standalone Zero-Dependency Fallback)
 */

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 1. Standard Laravel Execution Path (When Composer vendor is installed)
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);
    exit;
}

// 2. Standalone Lightweight Runner (Zero-Dependency Preview & Development Mode)
// Enables running immediately via: C:\xampp\php\php.exe -S localhost:8000 -t public
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Helper function to render blade-like templates or raw HTML views
function getActiveRole() {
    return $_SESSION['role'] ?? 'wisatawan';
}

function getUserName() {
    return $_SESSION['user_name'] ?? 'Wisatawan Pengunjung';
}

// Simple Router
if ($uri === '/' || $uri === '') {
    renderPublicHome();
} elseif ($uri === '/tentang-jumiang') {
    renderPublicAbout();
} elseif ($uri === '/peta-wisata') {
    renderPublicMap();
} elseif ($uri === '/katalog-umkm') {
    renderPublicCatalog();
} elseif (str_starts_with($uri, '/produk/')) {
    renderProductDetail();
} elseif ($uri === '/keranjang') {
    renderCart();
} elseif ($uri === '/checkout') {
    renderCheckout();
} elseif ($uri === '/reservasi-wisata') {
    renderReservation();
} elseif ($uri === '/login') {
    renderLogin();
} elseif ($uri === '/register') {
    renderRegister();
} elseif ($uri === '/dashboard') {
    renderDashboard();
} elseif ($uri === '/admin/products') {
    renderAdminProducts();
} elseif ($uri === '/admin/inventory') {
    renderAdminInventory();
} elseif ($uri === '/admin/financial') {
    renderAdminFinancial();
} elseif ($uri === '/admin/financial/profit-loss') {
    renderAdminProfitLoss();
} elseif ($uri === '/admin/orders') {
    renderAdminOrders();
} elseif ($uri === '/admin/reservations') {
    renderAdminReservations();
} elseif ($uri === '/admin/umkm') {
    renderAdminUmkm();
} elseif ($uri === '/admin/partnerships') {
    renderAdminPartnerships();
} elseif ($uri === '/admin/eis') {
    renderAdminEis();
} elseif ($uri === '/admin/reports') {
    renderAdminReports();
} elseif ($uri === '/admin/audit-logs') {
    renderAdminAudit();
} elseif ($uri === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
} elseif ($uri === '/demo-login') {
    $role = $_GET['role'] ?? 'super_admin';
    $_SESSION['role'] = $role;
    $_SESSION['user_name'] = match($role) {
        'super_admin' => 'Administrator Jumiang',
        'pengelola_wisata' => 'Pokdarwis Pantai Jumiang',
        'pelaku_umkm' => 'Ibu Maryam (Sentra Lorjuk)',
        'bendahara' => 'Bendahara Kawasan Jumiang',
        'pimpinan' => 'Kepala Desa Tanjung (Pimpinan)',
        default => 'Budi Wisatawan'
    };
    header('Location: /dashboard');
    exit;
} else {
    renderPublicHome();
}

// -------------------------------------------------------------------
// HTML Template Renderers
// -------------------------------------------------------------------

function renderLayoutHeader($title = "Jumiang Smart Tourism") {
    $role = getActiveRole();
    $name = getUserName();
    echo <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Pantai Jumiang Pamekasan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root { --ocean-blue: #0284c7; --ocean-dark: #0f172a; --seafoam: #06b6d4; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #334155; }
        h1,h2,h3,h4,h5,.brand-font { font-family: 'Outfit', sans-serif; }
        .navbar-marine { background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(10px); }
        .hero-banner { background: linear-gradient(135deg, rgba(15, 23, 42, 0.88), rgba(7, 89, 133, 0.85)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat; color: white; padding: 70px 0; border-bottom: 4px solid var(--seafoam); }
        .btn-ocean { background: linear-gradient(135deg, #0284c7, #075985); color: white; border: none; font-weight: 600; }
        .btn-ocean:hover { background: #0369a1; color: white; }
        .card-custom { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    </style>
</head>
<body>
    <div class="bg-warning text-dark py-1 text-center small fw-semibold">
        <i class="bi bi-info-circle-fill me-1"></i> [DATA DEMO / SIMULASI] Jumiang Smart Tourism & UMKM - Acuan Akademik RPS MANPRO (16 Pertemuan)
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-marine sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 brand-font fw-bold fs-4" href="/">
                <i class="bi bi-water text-info fs-3"></i>
                <span>JUMIANG <span class="text-info">SMART TOURISM</span></span>
            </a>
            <div class="collapse navbar-collapse show" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link text-white" href="/"><i class="bi bi-house-door me-1"></i> Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/tentang-jumiang"><i class="bi bi-compass me-1"></i> Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/peta-wisata"><i class="bi bi-geo-alt me-1"></i> Peta GIS</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/katalog-umkm"><i class="bi bi-shop me-1"></i> Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/reservasi-wisata"><i class="bi bi-ticket-perforated me-1"></i> Tiket</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/keranjang"><i class="bi bi-cart3"></i> <span class="badge bg-danger rounded-pill">2</span></a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-dark" href="/login">
                            <i class="bi bi-person-circle me-1"></i> Panel Multi-Role
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
HTML;
}

function renderLayoutFooter() {
    echo <<<HTML
    <footer class="bg-dark text-secondary py-5 mt-5">
        <div class="container text-center">
            <h5 class="text-white brand-font fw-bold"><i class="bi bi-water text-info me-2"></i>PANTAI JUMIANG</h5>
            <p class="small text-muted">Sistem Terintegrasi Wisata Bahari, Marketplace UMKM, dan Manajemen Keuangan Terpisah.<br>Desa Tanjung, Kec. Pademawu, Kab. Pamekasan, Madura.</p>
            <p class="small mb-0 text-secondary">&copy; 2026 Jumiang Smart Tourism & UMKM. Dilindungi Hak Cipta.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}

function renderPublicHome() {
    renderLayoutHeader("Beranda");
    echo <<<HTML
    <section class="hero-banner text-center text-lg-start">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3"><i class="bi bi-geo-alt-fill"></i> PADEMAWU, PAMEKASAN, MADURA</span>
                    <h1 class="display-4 fw-extrabold text-white mb-3 leading-tight">
                        Pesona Bahari Tebing & <br><span class="text-info">Ekosistem UMKM Jumiang</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        Platform terintegrasi informasi wisata, peta geospasial interaktif, etalase olahan lorjuk & batik pesisir, dan pembukuan keuangan mandiri pedagang lokal.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/peta-wisata" class="btn btn-ocean btn-lg px-4 rounded-pill shadow"><i class="bi bi-map-fill me-2"></i> Buka Peta Digital</a>
                        <a href="/katalog-umkm" class="btn btn-outline-light btn-lg px-4 rounded-pill"><i class="bi bi-shop me-2"></i> Belanja UMKM</a>
                        <a href="/dashboard" class="btn btn-info btn-lg px-4 rounded-pill fw-bold text-dark"><i class="bi bi-speedometer2 me-2"></i> Buka Dashboard ERP</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-4 text-white shadow-lg">
                        <h5 class="fw-bold text-info mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Cepat</h5>
                        <p class="small mb-2"><strong>Destinasi:</strong> Pantai Jumiang (Desa Tanjung)</p>
                        <p class="small mb-2"><strong>Jam Operasional:</strong> 06:00 - 18:00 WIB</p>
                        <p class="small mb-2"><strong>Tarif Retribusi [DEMO]:</strong> Rp 10.000 / orang</p>
                        <p class="small mb-3"><strong>Komoditas Unggulan:</strong> Olahan Lorjuk, Ikan Teri Krispi, Batik Pesisir Madura</p>
                        <a href="/reservasi-wisata" class="btn btn-info text-dark fw-bold rounded-pill w-100">Pesan Tiket Wisata Online</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white border-bottom">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-md-3 col-6"><h3 class="fw-bold text-primary">4 Spot</h3><small class="text-muted">Tebing & Pantai</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-success">3 Gerai</h3><small class="text-muted">UMKM Terverifikasi</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-info">7 Marker</h3><small class="text-muted">Peta Spasial WGS84</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-warning">Terpisah</h3><small class="text-muted">Arus Kas Usaha & Pribadi</small></div>
            </div>
        </div>
    </section>
HTML;
    renderLayoutFooter();
}

function renderPublicAbout() {
    renderLayoutHeader("Profil Pantai Jumiang");
    echo <<<HTML
    <div class="container py-5">
        <h2 class="fw-bold text-primary brand-font mb-3">Profil Kawasan Wisata Pantai Jumiang</h2>
        <p class="lead text-secondary">Terletak di Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Jawa Timur.</p>
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold text-dark">Karakteristik & Potensi Bahari</h5>
            <p class="text-muted">Pantai Jumiang memiliki ciri khas bebatuan karang tebing yang menjorok ke Selat Madura dan area pantai landai dengan ombak bersahabat. Kawasan ini merupakan sentra komoditas lorjuk (kerang bambu) segar yang menjadi mata pencaharian puluhan warga pesisir.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="card card-custom p-3"><h6>Puncak Tebing Jumiang</h6><small class="text-muted">Spot foto pemandangan laut dari atas tebing tinggi.</small></div></div>
            <div class="col-md-4"><div class="card card-custom p-3"><h6>Pantai Pasir Putih Landai</h6><small class="text-muted">Area rekreasi keluarga yang aman dan nyaman.</small></div></div>
            <div class="col-md-4"><div class="card card-custom p-3"><h6>Sunset Point</h6><small class="text-muted">Pemandangan matahari terbenam dengan siluet perahu nelayan.</small></div></div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderPublicMap() {
    renderLayoutHeader("Peta Wisata Digital (GIS)");
    echo <<<HTML
    <div class="container-fluid px-3 py-3">
        <div class="card card-custom p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold text-primary brand-font mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i>PETA INTERAKTIF PANTAI JUMIANG (LEAFLET.JS)</h4>
                <span class="badge bg-primary">WGS84 Datum</span>
            </div>
        </div>
        <div id="map" style="height: 580px; width: 100%; border-radius: 12px; border: 1px solid #cbd5e1;"></div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([-7.228912, 113.538120], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            var markers = [
                { lat: -7.228912, lng: 113.538120, title: "Puncak Tebing Karang Jumiang", cat: "Wisata" },
                { lat: -7.229450, lng: 113.537540, title: "Sunset Point Jumiang", cat: "Wisata" },
                { lat: -7.227800, lng: 113.536900, title: "Sentra Olahan Lorjuk Bu Maryam", cat: "UMKM" },
                { lat: -7.227200, lng: 113.536100, title: "Galeri Batik Pesisir Tanjung", cat: "UMKM" },
                { lat: -7.226500, lng: 113.535800, title: "Homestay Bahari Bu Sugiarti", cat: "Homestay" },
                { lat: -7.228100, lng: 113.537300, title: "Musholla Nurul Bahari & Toilet", cat: "Fasilitas" },
                { lat: -7.226900, lng: 113.536500, title: "Area Parkir Utama Wisata", cat: "Parkir" }
            ];

            markers.forEach(function(m) {
                var c = L.circleMarker([m.lat, m.lng], { radius: 9, fillColor: "#0284c7", color: "#fff", weight: 2, fillOpacity: 0.9 }).addTo(map);
                c.bindPopup("<strong>" + m.title + "</strong><br><small class='badge bg-info text-dark'>" + m.cat + "</small>");
            });
        });
    </script>
HTML;
    renderLayoutFooter();
}

function renderPublicCatalog() {
    renderLayoutHeader("Marketplace Produk UMKM");
    echo <<<HTML
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary brand-font mb-0">Etalase Digital Produk UMKM Jumiang</h2>
            <span class="badge bg-warning text-dark"><i class="bi bi-shield-check"></i> TRANSAKSI SIMULASI</span>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom p-3 h-100">
                    <div class="bg-light p-4 text-center rounded mb-3"><i class="bi bi-box-seam display-4 text-primary"></i></div>
                    <span class="badge bg-info text-dark align-self-start mb-2">Kuliner & Oleh-Oleh</span>
                    <h5 class="fw-bold mb-1">Rengginang Lorjuk Super</h5>
                    <small class="text-muted mb-2">Sentra Olahan Bu Maryam</small>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <strong class="text-primary fs-5">Rp 35.000</strong>
                        <a href="/keranjang" class="btn btn-ocean btn-sm rounded-pill px-3">+ Keranjang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 h-100">
                    <div class="bg-light p-4 text-center rounded mb-3"><i class="bi bi-box-seam display-4 text-primary"></i></div>
                    <span class="badge bg-info text-dark align-self-start mb-2">Kuliner & Oleh-Oleh</span>
                    <h5 class="fw-bold mb-1">Keripik Ikan Teri Krispi</h5>
                    <small class="text-muted mb-2">Sentra Olahan Bu Maryam</small>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <strong class="text-primary fs-5">Rp 20.000</strong>
                        <a href="/keranjang" class="btn btn-ocean btn-sm rounded-pill px-3">+ Keranjang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 h-100">
                    <div class="bg-light p-4 text-center rounded mb-3"><i class="bi bi-palette display-4 text-primary"></i></div>
                    <span class="badge bg-primary text-white align-self-start mb-2">Batik & Busana</span>
                    <h5 class="fw-bold mb-1">Kain Batik Tulis Motif Bahari</h5>
                    <small class="text-muted mb-2">Batik Pesisir Tanjung</small>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <strong class="text-primary fs-5">Rp 175.000</strong>
                        <a href="/keranjang" class="btn btn-ocean btn-sm rounded-pill px-3">+ Keranjang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderProductDetail() {
    global $uri;
    $slug = trim(substr($uri, strlen('/produk/')), '/');
    $catalog = [
        'rengginang-lorjuk-super' => [
            'name' => 'Rengginang Lorjuk Super Khas Jumiang',
            'category' => 'Kuliner & Oleh-Oleh Laut',
            'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
            'owner' => 'Ibu Maryam',
            'price' => 'Rp 35.000',
            'unit' => 'bungkus',
            'stock' => 45,
            'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
            'description' => 'Rengginang gurih dengan taburan lorjuk (kerang bambu) segar tangkapan pesisir Pantai Jumiang. Isi 500 gram, renyah dan nikmat.'
        ],
        'keripik-ikan-teri-krispi' => [
            'name' => 'Keripik Ikan Teri Krispi Gurih',
            'category' => 'Kuliner & Oleh-Oleh Laut',
            'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
            'owner' => 'Ibu Maryam',
            'price' => 'Rp 20.000',
            'unit' => 'bungkus',
            'stock' => 30,
            'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
            'description' => 'Olahan teri nasi segar dengan balutan tepung bumbu rempah Madura. Berat bersih 250 gram.'
        ],
        'kain-batik-tulis-motif-bahari' => [
            'name' => 'Kain Batik Tulis Motif Bahari Pamekasan',
            'category' => 'Batik & Busana Lokal',
            'merchant' => 'Batik Pesisir Jumiang Indah',
            'owner' => 'Pak Hidayat',
            'price' => 'Rp 175.000',
            'unit' => 'lembar',
            'stock' => 8,
            'address' => 'Sentra Seni & Kerajinan Tanjung No. 12, Pamekasan',
            'description' => 'Kain batik tulis halus dengan corak ombak, terumbu karang, dan perahu nelayan Jumiang. Ukuran 2x1.15 meter.'
        ],
    ];
    $item = $catalog[$slug] ?? [
        'name' => ucwords(str_replace('-', ' ', $slug ?: 'Produk UMKM Jumiang')),
        'category' => 'Produk UMKM Pantai Jumiang',
        'merchant' => 'Mitra UMKM Pantai Jumiang',
        'owner' => 'Pelaku UMKM Lokal',
        'price' => 'Rp 35.000',
        'unit' => 'pcs',
        'stock' => 15,
        'address' => 'Kawasan Wisata Pantai Jumiang, Desa Tanjung',
        'description' => 'Produk olahan berkualitas khas Pantai Jumiang, diproduksi langsung oleh pelaku UMKM lokal Desa Tanjung secara higienis dan terjamin.'
    ];
    $safeName = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
    $safeCategory = htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8');
    $safeMerchant = htmlspecialchars($item['merchant'], ENT_QUOTES, 'UTF-8');
    $safeOwner = htmlspecialchars($item['owner'], ENT_QUOTES, 'UTF-8');
    $safeAddress = htmlspecialchars($item['address'], ENT_QUOTES, 'UTF-8');
    $safeDesc = htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8');

    renderLayoutHeader($safeName . ' - Jumiang UMKM');
    echo <<<HTML
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/katalog-umkm">Katalog UMKM</a></li>
                <li class="breadcrumb-item active">{$safeName}</li>
            </ol>
        </nav>
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card card-custom p-4 bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 360px;">
                    <i class="bi bi-box-seam display-1 text-primary mb-3"></i>
                    <span class="badge bg-primary px-3 py-2">FOTO PRODUK UMKM [DEMO]</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-custom p-4">
                    <span class="badge bg-info bg-opacity-10 text-primary border align-self-start mb-2">{$safeCategory}</span>
                    <h2 class="fw-bold mb-2">{$safeName}</h2>
                    <p class="text-muted small mb-3"><i class="bi bi-shop me-1 text-secondary"></i> Dijual oleh: <strong class="text-dark">{$safeMerchant}</strong> (Pemilik: {$safeOwner})</p>
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <span class="small text-muted d-block">Harga Satuan</span>
                        <span class="display-6 fw-bold text-primary">{$item['price']}</span>
                        <span class="text-muted">/ {$item['unit']}</span>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                        <p class="text-secondary">{$safeDesc}</p>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6"><div class="p-3 border rounded-3"><span class="small text-muted d-block">Stok Tersedia</span><span class="fw-bold fs-5 text-success">{$item['stock']} {$item['unit']}</span></div></div>
                        <div class="col-sm-6"><div class="p-3 border rounded-3"><span class="small text-muted d-block">Lokasi Gerai</span><span class="fw-bold small text-dark d-block text-truncate">{$safeAddress}</span></div></div>
                    </div>
                    <a href="/keranjang" class="btn btn-ocean btn-lg w-100 rounded-pill fw-bold"><i class="bi bi-cart3 me-2"></i> Tambah ke Keranjang</a>
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderCart() {
    renderLayoutHeader("Keranjang Belanja");
    echo <<<HTML
    <div class="container py-5">
        <h3 class="fw-bold text-primary brand-font mb-4">Keranjang Belanja Anda</h3>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-custom p-4">
                    <table class="table align-middle">
                        <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Total</th></tr></thead>
                        <tbody>
                            <tr><td><strong>Rengginang Lorjuk Super</strong></td><td>Rp 35.000</td><td>2 bungkus</td><td class="fw-bold">Rp 70.000</td></tr>
                            <tr><td><strong>Keripik Ikan Teri Krispi</strong></td><td>Rp 20.000</td><td>1 bungkus</td><td class="fw-bold">Rp 20.000</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3">Total Pesanan: <span class="text-primary">Rp 90.000</span></h5>
                    <a href="/checkout" class="btn btn-ocean w-100 rounded-pill fw-bold">Lanjut ke Checkout</a>
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderCheckout() {
    renderLayoutHeader("Checkout Pesanan");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 max-w-700 mx-auto" style="max-width: 650px;">
            <h3 class="fw-bold text-primary mb-3">Simulasi Checkout Pesanan</h3>
            <div class="p-3 bg-light rounded mb-3">
                <span class="badge bg-warning text-dark mb-1">SIMULASI QRIS / VA</span>
                <p class="small text-muted mb-0">Total: <strong>Rp 90.000</strong> (2x Rengginang Lorjuk, 1x Keripik Teri)</p>
            </div>
            <a href="/dashboard" class="btn btn-success btn-lg w-100 rounded-pill fw-bold">Konfirmasi Pembayaran Simulasi Lunas</a>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderReservation() {
    renderLayoutHeader("Reservasi Wisata");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 mx-auto" style="max-width: 600px;">
            <h3 class="fw-bold text-primary mb-3">Reservasi Tiket Pantai Jumiang</h3>
            <p class="small text-muted mb-3">Tarif simulasi tiket masuk: <strong>Rp 10.000 / orang</strong></p>
            <form action="/dashboard" method="GET">
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Wisatawan</label>
                    <input type="number" class="form-control" value="2" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Kunjungan</label>
                    <input type="date" class="form-control" value="2026-11-05">
                </div>
                <button type="submit" class="btn btn-ocean w-100 rounded-pill fw-bold">Konfirmasi Booking Tiket</button>
            </form>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderLogin() {
    renderLayoutHeader("Masuk Sistem");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 p-md-5 mx-auto" style="max-width: 480px;">
            <h3 class="fw-bold text-center brand-font mb-2">Masuk ke Sistem</h3>
            <p class="small text-muted text-center mb-4">Akses Multi-Role Terintegrasi Jumiang ERP</p>
            
            <div class="p-3 bg-light rounded-3 border mb-3">
                <span class="badge bg-warning text-dark mb-2 small fw-bold">AKUN DEMO CEPAT (KLIK UNTUK MASUK):</span>
                <div class="d-grid gap-2">
                    <a href="/demo-login?role=super_admin" class="btn btn-outline-primary btn-sm text-start"><strong>1. Super Admin</strong> (Kontrol Penuh)</a>
                    <a href="/demo-login?role=pengelola_wisata" class="btn btn-outline-info btn-sm text-start text-dark"><strong>2. Pengelola Wisata</strong> (Tiket & Peta)</a>
                    <a href="/demo-login?role=pelaku_umkm" class="btn btn-outline-success btn-sm text-start"><strong>3. Pelaku UMKM</strong> (Produk & Kas Mandiri)</a>
                    <a href="/demo-login?role=bendahara" class="btn btn-outline-secondary btn-sm text-start"><strong>4. Bendahara</strong> (Arus Kas Kawasan)</a>
                    <a href="/demo-login?role=pimpinan" class="btn btn-outline-dark btn-sm text-start"><strong>5. Pimpinan / EIS</strong> (Analitik Strategis)</a>
                    <a href="/demo-login?role=wisatawan" class="btn btn-outline-warning btn-sm text-start text-dark"><strong>6. Wisatawan</strong> (Pesanan & Tiket)</a>
                </div>
            </div>
            <a href="/" class="btn btn-light btn-sm w-100 rounded-pill">Kembali ke Beranda</a>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderRegister() {
    renderLayoutHeader("Daftar Akun");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 mx-auto" style="max-width: 500px;">
            <h3 class="fw-bold text-center mb-3">Daftar Akun Wisatawan</h3>
            <form action="/login" method="GET">
                <div class="mb-3"><label class="form-label">Nama</label><input type="text" class="form-control" placeholder="Budi Santoso"></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" placeholder="budi@test.com"></div>
                <button type="submit" class="btn btn-ocean w-100 rounded-pill fw-bold">Daftar Sekarang</button>
            </form>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderDashboard() {
    $role = getActiveRole();
    $name = getUserName();
    renderLayoutHeader("Dashboard - " . ucfirst($role));
    echo <<<HTML
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark brand-font mb-0">Panel Dashboard: {$name}</h3>
                <span class="badge bg-primary px-3 py-1 text-uppercase">Role Aktif: {$role}</span>
            </div>
            <div>
                <a href="/logout" class="btn btn-outline-danger btn-sm rounded-pill px-3">Keluar Sistem</a>
            </div>
        </div>

        <!-- Quick Access Sub-modules -->
        <div class="card card-custom p-3 bg-white mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="/admin/products" class="btn btn-sm btn-outline-primary rounded-pill">Produk UMKM</a>
                <a href="/admin/inventory" class="btn btn-sm btn-outline-primary rounded-pill">Inventori & Stok</a>
                <a href="/admin/financial" class="btn btn-sm btn-outline-success rounded-pill">Buku Kas Terpisah</a>
                <a href="/admin/financial/profit-loss" class="btn btn-sm btn-outline-success rounded-pill">Laba Rugi</a>
                <a href="/admin/orders" class="btn btn-sm btn-outline-info rounded-pill text-dark">Pesanan Masuk</a>
                <a href="/admin/reservations" class="btn btn-sm btn-outline-warning rounded-pill text-dark">Tiket & Check-in</a>
                <a href="/admin/umkm" class="btn btn-sm btn-outline-dark rounded-pill">Verifikasi Mitra</a>
                <a href="/admin/partnerships" class="btn btn-sm btn-outline-dark rounded-pill">Kemitraan (MoU)</a>
                <a href="/admin/eis" class="btn btn-sm btn-primary rounded-pill">EIS Analytics</a>
                <a href="/admin/reports" class="btn btn-sm btn-secondary rounded-pill">Laporan</a>
                <a href="/admin/audit-logs" class="btn btn-sm btn-secondary rounded-pill">Audit Trail</a>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Omzet</small><h4 class="fw-bold text-primary mb-0">Rp 3.525.000</h4></div></div>
            <div class="col-md-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Mitra UMKM</small><h4 class="fw-bold text-success mb-0">3 Unit</h4></div></div>
            <div class="col-md-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Transaksi</small><h4 class="fw-bold text-info mb-0">2 Pesanan</h4></div></div>
            <div class="col-md-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Pengunjung</small><h4 class="fw-bold text-warning mb-0">6 Wisatawan</h4></div></div>
        </div>

        <!-- Chart -->
        <div class="card card-custom p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Tren Omzet Penjualan UMKM Jumiang</h5>
            <div style="height:250px;"><canvas id="chartDemo"></canvas></div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById('chartDemo').getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'],
                    datasets: [{
                        label: 'Omzet Bulanan (IDR)',
                        data: [1200000, 1850000, 2400000, 2100000, 3100000, 3525000],
                        borderColor: '#0284c7',
                        tension: 0.3,
                        fill: false
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
HTML;
    renderLayoutFooter();
}

function renderAdminProducts() {
    renderLayoutHeader("Produk UMKM");
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Manajemen Produk UMKM</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali ke Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white">
            <table class="table align-middle">
                <thead><tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td><strong>Rengginang Lorjuk Super</strong></td><td>Kuliner</td><td>Rp 35.000</td><td><span class="badge bg-success">45 bungkus</span></td><td><span class="badge bg-primary">Aktif</span></td></tr>
                    <tr><td><strong>Keripik Ikan Teri Krispi</strong></td><td>Kuliner</td><td>Rp 20.000</td><td><span class="badge bg-success">30 bungkus</span></td><td><span class="badge bg-primary">Aktif</span></td></tr>
                    <tr><td><strong>Terasi Asli Tanjung Super</strong></td><td>Kuliner</td><td>Rp 15.000</td><td><span class="badge bg-success">60 pcs</span></td><td><span class="badge bg-primary">Aktif</span></td></tr>
                    <tr><td><strong>Kain Batik Tulis Bahari</strong></td><td>Batik</td><td>Rp 175.000</td><td><span class="badge bg-danger">8 lembar (Menipis)</span></td><td><span class="badge bg-primary">Aktif</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminInventory() {
    renderLayoutHeader("Mutasi Inventori");
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Buku Mutasi Inventori & Stok</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white">
            <table class="table align-middle small">
                <thead><tr><th>Waktu</th><th>Produk</th><th>Tipe</th><th>Jumlah</th><th>Stok Akhir</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td>2026-11-02 08:00</td><td>Rengginang Lorjuk</td><td><span class="badge bg-success">IN</span></td><td>+50</td><td>50</td><td>Stok awal produksi musim lorjuk</td></tr>
                    <tr><td>2026-11-02 09:15</td><td>Rengginang Lorjuk</td><td><span class="badge bg-danger">OUT</span></td><td>-5</td><td>45</td><td>Pesanan #INV-JMI-001</td></tr>
                    <tr><td>2026-11-02 08:30</td><td>Kain Batik Tulis</td><td><span class="badge bg-danger">OUT</span></td><td>-2</td><td>8</td><td>Pesanan #INV-JMI-002</td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminFinancial() {
    renderLayoutHeader("Buku Kas Mandiri");
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-0">Buku Kas Keuangan UMKM Mandiri</h3>
                <small class="text-muted">Pemisahan Rekening Usaha vs Tabungan Pribadi</small>
            </div>
            <div>
                <a href="/admin/financial/profit-loss" class="btn btn-success btn-sm rounded-pill px-3">Lihat Laba Rugi</a>
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Dashboard</a>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card card-custom p-3 border-start border-4 border-primary bg-white">
                    <span class="badge bg-primary align-self-start mb-2">REKENING KAS USAHA</span>
                    <h5 class="fw-bold mb-1">Kas Operasional Gerai Lorjuk</h5>
                    <div class="fs-4 fw-bold text-primary">Rp 1.525.000</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-3 border-start border-4 border-secondary bg-white">
                    <span class="badge bg-secondary align-self-start mb-2">REKENING PRIBADI</span>
                    <h5 class="fw-bold mb-1">Tabungan Rumah Tangga Bu Maryam</h5>
                    <div class="fs-4 fw-bold text-secondary">Rp 500.000</div>
                </div>
            </div>
        </div>
        <div class="card card-custom p-4 bg-white">
            <h6 class="fw-bold mb-3">Jurnal Mutasi Kas Harian:</h6>
            <table class="table align-middle small">
                <thead><tr><th>Tanggal</th><th>Rekening</th><th>Kategori</th><th>Jenis</th><th>Nominal</th></tr></thead>
                <tbody>
                    <tr><td>01/11/2026</td><td>Kas Usaha</td><td>Bahan Baku Lorjuk</td><td><span class="badge bg-danger">EXPENSE</span></td><td class="text-danger fw-bold">- Rp 350.000</td></tr>
                    <tr><td>02/11/2026</td><td>Kas Usaha</td><td>Penjualan Offline</td><td><span class="badge bg-success">INCOME</span></td><td class="text-success fw-bold">+ Rp 750.000</td></tr>
                    <tr><td>02/11/2026</td><td>Kas Usaha</td><td>Penjualan Marketplace</td><td><span class="badge bg-success">INCOME</span></td><td class="text-success fw-bold">+ Rp 175.000</td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminProfitLoss() {
    renderLayoutHeader("Laporan Laba Rugi");
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Laporan Laba Rugi Sederhana</h3>
            <div>
                <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.print()">Cetak PDF</button>
                <a href="/admin/financial" class="btn btn-light btn-sm rounded-pill">Buku Kas</a>
            </div>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="p-3 bg-success bg-opacity-10 rounded"><h6>Total Pemasukan:</h6><strong class="fs-4 text-success">Rp 925.000</strong></div></div>
                <div class="col-md-4"><div class="p-3 bg-danger bg-opacity-10 rounded"><h6>Total Pengeluaran:</h6><strong class="fs-4 text-danger">Rp 400.000</strong></div></div>
                <div class="col-md-4"><div class="p-3 bg-primary bg-opacity-10 rounded"><h6>Laba Bersih:</h6><strong class="fs-4 text-primary">+ Rp 525.000</strong></div></div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminOrders() {
    renderLayoutHeader("Pesanan Masuk");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Daftar Transaksi Pesanan Masuk</h3>
        <div class="card card-custom p-4 bg-white">
            <table class="table align-middle">
                <thead><tr><th>Invoice</th><th>Waktu</th><th>Pemesan</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td><strong>INV-JMI-20261102-001</strong></td><td>02/11/2026</td><td>Budi Wisatawan</td><td>Rp 175.000</td><td><span class="badge bg-success">COMPLETED</span></td></tr>
                    <tr><td><strong>INV-JMI-20261102-002</strong></td><td>02/11/2026</td><td>Budi Wisatawan</td><td>Rp 350.000</td><td><span class="badge bg-success">PAID</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminReservations() {
    renderLayoutHeader("Reservasi Wisata");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Reservasi & Validasi Kedatangan</h3>
        <div class="card card-custom p-4 bg-white">
            <table class="table align-middle">
                <thead><tr><th>Kode Booking</th><th>Pemesan</th><th>Tgl Kunjungan</th><th>Orang</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td><strong>RSV-JMI-20261102-001</strong></td><td>Budi Wisatawan</td><td>08 Nov 2026</td><td>4 Orang</td><td><span class="badge bg-primary">CONFIRMED</span></td></tr>
                    <tr><td><strong>RSV-JMI-20261102-002</strong></td><td>Budi Wisatawan</td><td>02 Nov 2026</td><td>2 Orang</td><td><span class="badge bg-success">ATTENDED</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminUmkm() {
    renderLayoutHeader("Mitra UMKM");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Verifikasi Mitra Pelaku UMKM</h3>
        <div class="card card-custom p-4 bg-white">
            <table class="table align-middle">
                <thead><tr><th>Nama Toko</th><th>Pemilik</th><th>Kategori</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td><strong>Sentra Olahan Lorjuk Bu Maryam</strong></td><td>Ibu Maryam</td><td>Kuliner</td><td><span class="badge bg-success">VERIFIED</span></td></tr>
                    <tr><td><strong>Batik Pesisir Jumiang Indah</strong></td><td>Pak Hidayat</td><td>Kerajinan</td><td><span class="badge bg-success">VERIFIED</span></td></tr>
                    <tr><td><strong>Homestay Bahari Jumiang Asri</strong></td><td>Ibu Sugiarti</td><td>Homestay</td><td><span class="badge bg-success">VERIFIED</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminPartnerships() {
    renderLayoutHeader("Kemitraan");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Arsip Kemitraan & MoU</h3>
        <div class="card card-custom p-4 bg-white">
            <p>1. Dinas Kebudayaan & Pariwisata Kab. Pamekasan (Pemerintah, Status: ACTIVE)</p>
            <p>2. Universitas Madura KKN Tematik (Akademisi, Status: ACTIVE)</p>
            <p>3. Asosiasi Pengusaha Batik Pamekasan (Komunitas, Status: ACTIVE)</p>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminEis() {
    renderLayoutHeader("EIS Analytics");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Executive Information System (EIS) Lengkap</h3>
        <div class="card card-custom p-4 bg-white mb-4">
            <p>Ringkasan Analitik Makro: Total Omzet Terkumpul <strong>Rp 3.525.000</strong> dari 3 UMKM terverifikasi dan 6 wisatawan terdata.</p>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminReports() {
    renderLayoutHeader("Laporan");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Pusat Rekapitulasi Laporan</h3>
        <div class="card card-custom p-4 bg-white"><p>Laporan transaksi dan keuangan siap diekspor ke PDF dan Excel.</p></div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminAudit() {
    renderLayoutHeader("Audit Trail");
    echo <<<HTML
    <div class="container py-4">
        <h3 class="fw-bold text-dark mb-3">Log Audit Trail Aktivitas Pengguna</h3>
        <div class="card card-custom p-4 bg-white">
            <table class="table small">
                <thead><tr><th>Waktu</th><th>Aksi</th><th>User</th></tr></thead>
                <tbody>
                    <tr><td>2026-11-02 08:30</td><td>SYSTEM_INIT</td><td>Administrator Jumiang</td></tr>
                    <tr><td>2026-11-02 08:45</td><td>PRICE_UPDATE</td><td>Ibu Maryam</td></tr>
                    <tr><td>2026-11-02 09:15</td><td>ORDER_CHECKOUT</td><td>Budi Wisatawan</td></tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}
