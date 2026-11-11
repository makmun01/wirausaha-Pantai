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
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($uri === '/favicon.ico') {
    http_response_code(204);
    exit;
}

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rp($amount): string {
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function consumeFlashHtml(): string {
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return '';
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $type = in_array($flash['type'] ?? '', ['success', 'danger', 'warning', 'info'], true)
        ? $flash['type']
        : 'info';
    $msg = e($flash['message'] ?? '');
    return "<div class='container mt-3'><div class='alert alert-{$type} alert-dismissible fade show shadow-sm' role='alert'>{$msg}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div></div>";
}

function initPreviewState(bool $reset = false): void {
    if (!$reset && isset($_SESSION['preview']) && is_array($_SESSION['preview'])) {
        return;
    }

    $_SESSION['preview'] = [
        'products' => [
            1 => [
                'id' => 1,
                'umkm_merchant_id' => 1,
                'slug' => 'rengginang-lorjuk-super',
                'name' => 'Rengginang Lorjuk Super Khas Jumiang',
                'category' => 'Kuliner & Oleh-Oleh Laut',
                'category_key' => 'kuliner',
                'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
                'owner' => 'Ibu Maryam',
                'price' => 35000,
                'unit' => 'bungkus',
                'stock' => 45,
                'low_stock_threshold' => 5,
                'is_active' => true,
                'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
                'description' => 'Rengginang gurih dengan taburan lorjuk (kerang bambu) segar tangkapan pesisir Pantai Jumiang. Isi 500 gram, renyah dan nikmat.'
            ],
            2 => [
                'id' => 2,
                'umkm_merchant_id' => 1,
                'slug' => 'keripik-ikan-teri-krispi',
                'name' => 'Keripik Ikan Teri Krispi Gurih',
                'category' => 'Kuliner & Oleh-Oleh Laut',
                'category_key' => 'kuliner',
                'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
                'owner' => 'Ibu Maryam',
                'price' => 20000,
                'unit' => 'bungkus',
                'stock' => 30,
                'low_stock_threshold' => 5,
                'is_active' => true,
                'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
                'description' => 'Olahan teri nasi segar dengan balutan tepung bumbu rempah Madura. Berat bersih 250 gram.'
            ],
            3 => [
                'id' => 3,
                'umkm_merchant_id' => 1,
                'slug' => 'terasi-asli-tanjung',
                'name' => 'Terasi Asli Tanjung Madura (Super)',
                'category' => 'Kuliner & Oleh-Oleh Laut',
                'category_key' => 'kuliner',
                'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
                'owner' => 'Ibu Maryam',
                'price' => 15000,
                'unit' => 'pcs',
                'stock' => 60,
                'low_stock_threshold' => 10,
                'is_active' => true,
                'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
                'description' => 'Terasi rebon murni tanpa bahan pengawet buatan, aroma khas dan lezat untuk sambal pesisir.'
            ],
            4 => [
                'id' => 4,
                'umkm_merchant_id' => 2,
                'slug' => 'kain-batik-tulis-motif-bahari',
                'name' => 'Kain Batik Tulis Motif Bahari Pamekasan',
                'category' => 'Batik & Busana Lokal',
                'category_key' => 'batik',
                'merchant' => 'Batik Pesisir Jumiang Indah',
                'owner' => 'Pak Hidayat',
                'price' => 175000,
                'unit' => 'lembar',
                'stock' => 8,
                'low_stock_threshold' => 2,
                'is_active' => true,
                'address' => 'Sentra Seni & Kerajinan Tanjung No. 12, Pamekasan',
                'description' => 'Kain batik tulis halus dengan corak ombak, terumbu karang, dan perahu nelayan Jumiang. Ukuran 2x1.15 meter.'
            ],
            5 => [
                'id' => 5,
                'umkm_merchant_id' => 3,
                'slug' => 'sewa-kamar-homestay',
                'name' => 'Sewa Kamar Homestay Standard View Laut',
                'category' => 'Penginapan & Homestay',
                'category_key' => 'homestay',
                'merchant' => 'Homestay Bahari Jumiang Asri',
                'owner' => 'Ibu Sugiarti',
                'price' => 250000,
                'unit' => 'malam',
                'stock' => 4,
                'low_stock_threshold' => 1,
                'is_active' => true,
                'address' => 'Dusun Nelayan Timur, Desa Tanjung, Pademawu',
                'description' => 'Kamar homestay ber-AC, kamar mandi dalam, sarapan khas pesisir, dan pemandangan laut Pantai Jumiang.'
            ],
        ],
        'cart' => [
            1 => 2,
            2 => 1,
        ],
        'merchants' => [
            1 => ['id' => 1, 'business_name' => 'Sentra Olahan Lorjuk Bu Maryam', 'owner_name' => 'Ibu Maryam', 'category' => 'KULINER', 'phone_number' => '081234567892', 'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang', 'verification_status' => 'VERIFIED', 'revenue' => 175000],
            2 => ['id' => 2, 'business_name' => 'Batik Pesisir Jumiang Indah', 'owner_name' => 'Pak Hidayat', 'category' => 'KERAJINAN', 'phone_number' => '081234567893', 'address' => 'Sentra Seni & Kerajinan Tanjung No. 12, Pamekasan', 'verification_status' => 'VERIFIED', 'revenue' => 350000],
            3 => ['id' => 3, 'business_name' => 'Homestay Bahari Jumiang Asri', 'owner_name' => 'Ibu Sugiarti', 'category' => 'HOMESTAY', 'phone_number' => '081234567894', 'address' => 'Dusun Nelayan Timur, Desa Tanjung, Pademawu', 'verification_status' => 'VERIFIED', 'revenue' => 0],
        ],
        'accounts' => [
            1 => ['id' => 1, 'account_name' => 'Kas Operasional Usaha Lorjuk', 'account_type' => 'BUSINESS', 'current_balance' => 1525000],
            2 => ['id' => 2, 'account_name' => 'Tabungan Rumah Tangga Pribadi Bu Maryam', 'account_type' => 'PERSONAL', 'current_balance' => 500000],
        ],
        'transactions' => [
            ['date' => '2026-11-01', 'account_id' => 1, 'account' => 'Kas Usaha', 'category' => 'Pembelian Bahan Baku', 'description' => 'Pembelian 10 kg lorjuk segar dari perahu nelayan Tanjung', 'type' => 'EXPENSE', 'amount' => 350000],
            ['date' => '2026-11-01', 'account_id' => 1, 'account' => 'Kas Usaha', 'category' => 'Biaya Kemasan', 'description' => 'Pembelian standing pouch dan kardus oleh-oleh', 'type' => 'EXPENSE', 'amount' => 50000],
            ['date' => '2026-11-02', 'account_id' => 1, 'account' => 'Kas Usaha', 'category' => 'Penjualan Offline', 'description' => 'Penjualan offline pengunjung rombongan di gerai pantai', 'type' => 'INCOME', 'amount' => 750000],
            ['date' => '2026-11-02', 'account_id' => 1, 'account' => 'Kas Usaha', 'category' => 'Penjualan Marketplace', 'description' => 'Penerimaan otomatis pesanan #INV-JMI-20261102-001', 'type' => 'INCOME', 'amount' => 175000],
        ],
        'inventory_logs' => [
            ['time' => '2026-11-02 08:00', 'product' => 'Rengginang Lorjuk Super Khas Jumiang', 'type' => 'IN', 'qty' => '+50', 'prev' => 0, 'curr' => 50, 'notes' => 'Stok awal produksi musim tangkap lorjuk'],
            ['time' => '2026-11-02 08:15', 'product' => 'Keripik Ikan Teri Krispi Gurih', 'type' => 'IN', 'qty' => '+30', 'prev' => 0, 'curr' => 30, 'notes' => 'Stok awal produksi keripik teri'],
            ['time' => '2026-11-02 08:20', 'product' => 'Terasi Asli Tanjung Madura (Super)', 'type' => 'IN', 'qty' => '+60', 'prev' => 0, 'curr' => 60, 'notes' => 'Stok fermentasi terasi matang'],
            ['time' => '2026-11-02 08:25', 'product' => 'Kain Batik Tulis Motif Bahari Pamekasan', 'type' => 'IN', 'qty' => '+10', 'prev' => 0, 'curr' => 10, 'notes' => 'Hasil celupan canting batik tulis'],
            ['time' => '2026-11-02 08:30', 'product' => 'Kain Batik Tulis Motif Bahari Pamekasan', 'type' => 'OUT', 'qty' => '-2', 'prev' => 10, 'curr' => 8, 'notes' => 'Pembelian invoice #INV-JMI-20261102-002'],
            ['time' => '2026-11-02 09:15', 'product' => 'Rengginang Lorjuk Super Khas Jumiang', 'type' => 'OUT', 'qty' => '-5', 'prev' => 50, 'curr' => 45, 'notes' => 'Pembelian invoice #INV-JMI-20261102-001'],
        ],
        'orders' => [
            1 => ['id' => 1, 'invoice' => 'INV-JMI-20261102-001', 'time' => '02/11/2026', 'customer' => 'Budi Wisatawan', 'items_text' => '5x Rengginang Lorjuk Super', 'total' => 175000, 'status' => 'COMPLETED', 'method' => 'SIMULATION_QRIS'],
            2 => ['id' => 2, 'invoice' => 'INV-JMI-20261102-002', 'time' => '02/11/2026', 'customer' => 'Budi Wisatawan', 'items_text' => '2x Kain Batik Tulis Bahari', 'total' => 350000, 'status' => 'PAID', 'method' => 'SIMULATION_VA'],
        ],
        'reservations' => [
            1 => ['id' => 1, 'code' => 'RSV-JMI-20261102-001', 'customer' => 'Budi Wisatawan', 'date' => '2026-11-08', 'visitors' => 4, 'package' => 'Paket Wisata Keluarga & Tebing', 'total' => 40000, 'status' => 'CONFIRMED', 'check_in' => '-'],
            2 => ['id' => 2, 'code' => 'RSV-JMI-20261102-002', 'customer' => 'Budi Wisatawan', 'date' => '2026-11-02', 'visitors' => 2, 'package' => 'Tiket Masuk Reguler', 'total' => 20000, 'status' => 'ATTENDED', 'check_in' => '2026-11-02 09:00'],
        ],
        'partnerships' => [
            ['partner' => 'Dinas Kebudayaan & Pariwisata Kab. Pamekasan', 'type' => 'PEMERINTAH', 'contact' => 'Bpk. Ahmad Fauzi (081122334455)', 'period' => '2026-2028', 'status' => 'ACTIVE'],
            ['partner' => 'Universitas Madura (KKN Tematik)', 'type' => 'AKADEMISI', 'contact' => 'Dr. Subhan (081299887766)', 'period' => 'Jul - Agu 2026', 'status' => 'ACTIVE'],
            ['partner' => 'Asosiasi Pengusaha Batik Pamekasan', 'type' => 'KOMUNITAS', 'contact' => 'Ibu Nurul (081377665544)', 'period' => '2026-2027', 'status' => 'ACTIVE'],
        ],
        'audit_logs' => [
            ['time' => '2026-11-02 08:30', 'action' => 'SYSTEM_INIT', 'user' => 'Administrator Jumiang', 'detail' => 'Initialized Week 01 - 16'],
            ['time' => '2026-11-02 08:45', 'action' => 'PRICE_UPDATE', 'user' => 'Ibu Maryam', 'detail' => 'Rengginang Lorjuk: Rp 30.000 -> Rp 35.000'],
            ['time' => '2026-11-02 09:15', 'action' => 'ORDER_CHECKOUT', 'user' => 'Budi Wisatawan', 'detail' => 'Invoice INV-JMI-20261102-001 (Rp 175.000)'],
        ],
        'last_order' => null,
        'last_reservation' => null,
        'custom_etl_report' => null,
    ];
}

initPreviewState();

function appendAuditLog(string $action, string $detail): void {
    array_unshift($_SESSION['preview']['audit_logs'], [
        'time' => date('Y-m-d H:i:s'),
        'action' => $action,
        'user' => getUserName(),
        'detail' => $detail,
    ]);
}

function getActiveRole(): string {
    return $_SESSION['role'] ?? 'wisatawan';
}

function getUserName(): string {
    return $_SESSION['user_name'] ?? 'Wisatawan Pengunjung';
}

function requireRole(array $allowedRoles): void {
    $role = getActiveRole();
    if ($role === 'super_admin' || in_array($role, $allowedRoles, true)) {
        return;
    }
    renderForbidden($role, $allowedRoles);
    exit;
}

// Health check & REST API v1 JSON Router
if ($uri === '/health') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => 'ok',
        'app' => 'Jumiang Smart Tourism & UMKM',
        'mode' => 'standalone-preview',
    ]);
    exit;
}

if (str_starts_with($uri, '/api/v1/')) {
    handleApiRequest($uri, $method);
    exit;
}

// Web Router (GET & Interactive Preview POST Actions)
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
    if ($method === 'POST') {
        handleCartPost();
    } else {
        renderCart();
    }
} elseif ($uri === '/checkout') {
    if ($method === 'POST') {
        handleCheckoutPost();
    } else {
        renderCheckout();
    }
} elseif ($uri === '/reservasi-wisata') {
    if ($method === 'POST') {
        handleReservationPost();
    } else {
        renderReservation();
    }
} elseif ($uri === '/login') {
    if ($method === 'POST') {
        handleDemoCredentialsLogin();
    } else {
        renderLogin();
    }
} elseif ($uri === '/register') {
    if ($method === 'POST') {
        handleRegisterPost();
    } else {
        renderRegister();
    }
} elseif ($uri === '/dashboard') {
    renderDashboard();
} elseif ($uri === '/admin/dashboard') {
    requireRole(['super_admin', 'pengelola_wisata', 'pelaku_umkm', 'bendahara', 'pimpinan']);
    renderDashboard();
} elseif ($uri === '/admin/products') {
    requireRole(['pelaku_umkm', 'super_admin']);
    if ($method === 'POST') {
        handleAdminProductsPost();
    } else {
        renderAdminProducts();
    }
} elseif ($uri === '/admin/inventory') {
    requireRole(['pelaku_umkm', 'super_admin']);
    if ($method === 'POST') {
        handleAdminInventoryPost();
    } else {
        renderAdminInventory();
    }
} elseif ($uri === '/admin/financial') {
    requireRole(['pelaku_umkm', 'bendahara', 'super_admin']);
    if ($method === 'POST') {
        handleAdminFinancialPost();
    } else {
        renderAdminFinancial();
    }
} elseif ($uri === '/admin/financial/profit-loss') {
    requireRole(['pelaku_umkm', 'bendahara', 'super_admin']);
    renderAdminProfitLoss();
} elseif ($uri === '/admin/orders') {
    requireRole(['pelaku_umkm', 'pengelola_wisata', 'super_admin']);
    if ($method === 'POST') {
        handleAdminOrdersPost();
    } else {
        renderAdminOrders();
    }
} elseif ($uri === '/admin/reservations') {
    requireRole(['pengelola_wisata', 'super_admin']);
    if ($method === 'POST') {
        handleAdminReservationsPost();
    } else {
        renderAdminReservations();
    }
} elseif ($uri === '/admin/umkm') {
    requireRole(['super_admin']);
    if ($method === 'POST') {
        handleAdminUmkmPost();
    } else {
        renderAdminUmkm();
    }
} elseif ($uri === '/admin/partnerships') {
    requireRole(['super_admin']);
    if ($method === 'POST') {
        handleAdminPartnershipsPost();
    } else {
        renderAdminPartnerships();
    }
} elseif ($uri === '/admin/eis') {
    requireRole(['pimpinan', 'bendahara', 'super_admin']);
    renderAdminEis();
} elseif ($uri === '/admin/reports') {
    requireRole(['pimpinan', 'bendahara', 'super_admin']);
    renderAdminReports();
} elseif ($uri === '/admin/audit-logs') {
    requireRole(['super_admin']);
    renderAdminAudit();
} elseif ($uri === '/admin/etl') {
    requireRole(['super_admin']);
    if ($method === 'POST') {
        handleAdminEtlPost();
    } else {
        renderAdminEtl();
    }
} elseif ($uri === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
} elseif ($uri === '/demo-login') {
    $role = $_GET['role'] ?? 'super_admin';
    $allowedRoles = ['super_admin', 'pengelola_wisata', 'pelaku_umkm', 'bendahara', 'pimpinan', 'wisatawan'];
    if (!in_array($role, $allowedRoles, true)) {
        $role = 'wisatawan';
    }
    $_SESSION['role'] = $role;
    $_SESSION['user_name'] = match($role) {
        'super_admin' => 'Administrator Jumiang',
        'pengelola_wisata' => 'Pokdarwis Pantai Jumiang',
        'pelaku_umkm' => 'Ibu Maryam (Sentra Lorjuk)',
        'bendahara' => 'Bendahara Kawasan Jumiang',
        'pimpinan' => 'Kepala Desa Tanjung (Pimpinan)',
        default => 'Budi Wisatawan'
    };
    setFlash('info', 'Mode Demo aktif sebagai ' . $_SESSION['user_name'] . ' (' . $role . ').');
    header('Location: /dashboard');
    exit;
} else {
    renderNotFound($uri);
}

// -------------------------------------------------------------------
// Interactive POST Handlers (Standalone Preview Simulation)
// -------------------------------------------------------------------

function handleCartPost(): void {
    $action = $_POST['action'] ?? 'add';
    $productId = (int) ($_POST['product_id'] ?? 0);
    $products = $_SESSION['preview']['products'];
    $cart = &$_SESSION['preview']['cart'];

    if ($action === 'add' && isset($products[$productId])) {
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $current = (int) ($cart[$productId] ?? 0);
        $next = min($products[$productId]['stock'], $current + $qty);
        $cart[$productId] = max(1, $next);
        setFlash('success', 'Produk "' . $products[$productId]['name'] . '" ditambahkan ke keranjang.');
    } elseif ($action === 'update') {
        $quantities = $_POST['quantities'] ?? [];
        if (is_array($quantities)) {
            foreach ($quantities as $pid => $q) {
                $pid = (int) $pid;
                $q = (int) $q;
                if ($q <= 0) {
                    unset($cart[$pid]);
                } elseif (isset($products[$pid])) {
                    $cart[$pid] = min($products[$pid]['stock'], $q);
                }
            }
            setFlash('success', 'Jumlah item keranjang berhasil diperbarui.');
        }
    } elseif ($action === 'remove' && isset($cart[$productId])) {
        unset($cart[$productId]);
        setFlash('info', 'Produk dihapus dari keranjang.');
    }

    header('Location: /keranjang');
    exit;
}

function handleCheckoutPost(): void {
    $cart = $_SESSION['preview']['cart'] ?? [];
    $products = $_SESSION['preview']['products'];
    if (empty($cart)) {
        setFlash('warning', 'Keranjang belanja kosong. Tambahkan produk terlebih dahulu.');
        header('Location: /katalog-umkm');
        exit;
    }

    $method = in_array($_POST['payment_method'] ?? '', ['SIMULATION_QRIS', 'SIMULATION_VA', 'CASH_ON_LOCATION'], true)
        ? $_POST['payment_method']
        : 'SIMULATION_QRIS';
    $delivery = in_array($_POST['delivery_option'] ?? '', ['PICKUP', 'DELIVERY'], true)
        ? $_POST['delivery_option']
        : 'PICKUP';

    $total = 0;
    $summaryParts = [];
    foreach ($cart as $pid => $qty) {
        if (!isset($products[$pid]) || $qty < 1) {
            continue;
        }
        $qty = min($qty, $products[$pid]['stock']);
        if ($qty < 1) {
            continue;
        }
        $_SESSION['preview']['products'][$pid]['stock'] -= $qty;
        $total += $products[$pid]['price'] * $qty;
        $summaryParts[] = "{$qty}x {$products[$pid]['name']}";
        array_unshift($_SESSION['preview']['inventory_logs'], [
            'time' => date('Y-m-d H:i'),
            'product' => $products[$pid]['name'],
            'type' => 'OUT',
            'qty' => '-' . $qty,
            'prev' => $products[$pid]['stock'],
            'curr' => $_SESSION['preview']['products'][$pid]['stock'],
            'notes' => 'Simulasi Checkout (' . $method . ')',
        ]);
    }

    if ($total <= 0) {
        setFlash('danger', 'Stok produk tidak mencukupi untuk checkout.');
        header('Location: /keranjang');
        exit;
    }

    $nextId = count($_SESSION['preview']['orders']) + 1;
    $invoice = 'INV-JMI-' . date('Ymd') . '-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
    $order = [
        'id' => $nextId,
        'invoice' => $invoice,
        'time' => date('d/m/Y H:i'),
        'customer' => getUserName(),
        'items_text' => implode(', ', $summaryParts) . " ({$delivery})",
        'total' => $total,
        'status' => 'PAID',
        'method' => $method,
    ];
    $_SESSION['preview']['orders'][$nextId] = $order;
    $_SESSION['preview']['last_order'] = $order;
    $_SESSION['preview']['cart'] = [];

    // Update Merchant 1 Business cash automatically for transparency
    $_SESSION['preview']['accounts'][1]['current_balance'] += $total;
    array_unshift($_SESSION['preview']['transactions'], [
        'date' => date('Y-m-d'),
        'account_id' => 1,
        'account' => 'Kas Usaha',
        'category' => 'Penjualan Marketplace',
        'description' => "Penerimaan otomatis pesanan #{$invoice} ({$method})",
        'type' => 'INCOME',
        'amount' => $total,
    ]);
    appendAuditLog('ORDER_CHECKOUT', "Checkout simulasi {$invoice} senilai " . rp($total));

    setFlash('success', "Simulasi Pembayaran Lunas Berhasil! Invoice: {$invoice}");
    header('Location: /checkout?invoice=' . urlencode($invoice));
    exit;
}

function handleReservationPost(): void {
    $visitors = (int) ($_POST['visitor_count'] ?? 0);
    $date = trim((string) ($_POST['visit_date'] ?? date('Y-m-d')));
    $package = trim((string) ($_POST['package_name'] ?? 'Tiket Masuk Reguler'));

    if ($visitors < 1 || $visitors > 500) {
        setFlash('danger', 'Jumlah wisatawan harus antara 1 sampai 500 orang (BVA Validation).');
        header('Location: /reservasi-wisata');
        exit;
    }

    $nextId = count($_SESSION['preview']['reservations']) + 1;
    $code = 'RSV-JMI-' . date('Ymd') . '-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
    $total = $visitors * 10000;
    $res = [
        'id' => $nextId,
        'code' => $code,
        'customer' => getUserName(),
        'date' => $date,
        'visitors' => $visitors,
        'package' => $package !== '' ? $package : 'Tiket Masuk Reguler',
        'total' => $total,
        'status' => 'CONFIRMED',
        'check_in' => '-',
    ];
    $_SESSION['preview']['reservations'][$nextId] = $res;
    $_SESSION['preview']['last_reservation'] = $res;
    appendAuditLog('RESERVATION_CREATED', "Booking {$code} untuk {$visitors} orang (" . rp($total) . ')');

    setFlash('success', "Reservasi berhasil! Kode Booking Anda: {$code}");
    header('Location: /reservasi-wisata?booked=' . urlencode($code));
    exit;
}

function handleDemoCredentialsLogin(): void {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $map = [
        'admin@jumiang.test' => ['super_admin', 'Administrator Jumiang'],
        'pengelola@jumiang.test' => ['pengelola_wisata', 'Pokdarwis Pantai Jumiang'],
        'umkm.maryam@jumiang.test' => ['pelaku_umkm', 'Ibu Maryam (Sentra Lorjuk)'],
        'bendahara@jumiang.test' => ['bendahara', 'Bendahara Kawasan Jumiang'],
        'pimpinan@jumiang.test' => ['pimpinan', 'Kepala Desa Tanjung (Pimpinan)'],
        'wisatawan@jumiang.test' => ['wisatawan', 'Budi Wisatawan'],
    ];

    if (!isset($map[$email])) {
        setFlash('danger', 'Email demo tidak dikenali. Gunakan tombol Akun Demo Cepat atau email @jumiang.test.');
        header('Location: /login');
        exit;
    }

    $_SESSION['role'] = $map[$email][0];
    $_SESSION['user_name'] = $map[$email][1];
    setFlash('success', 'Berhasil masuk sebagai ' . $map[$email][1] . '.');
    header('Location: /dashboard');
    exit;
}

function handleRegisterPost(): void {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        setFlash('danger', 'Nama valid, email valid, dan password minimal 6 karakter wajib diisi.');
        header('Location: /register');
        exit;
    }

    $_SESSION['role'] = 'wisatawan';
    $_SESSION['user_name'] = $name;
    appendAuditLog('USER_REGISTER_DEMO', "Registrasi wisatawan demo: {$email}");
    setFlash('success', "Pendaftaran akun demo untuk {$name} berhasil! Anda masuk sebagai Wisatawan.");
    header('Location: /dashboard');
    exit;
}

function handleAdminProductsPost(): void {
    $name = trim((string) ($_POST['name'] ?? ''));
    $category = trim((string) ($_POST['category'] ?? 'Kuliner & Oleh-Oleh Laut'));
    $price = (float) ($_POST['price'] ?? -1);
    $stock = (int) ($_POST['stock'] ?? -1);
    $unit = trim((string) ($_POST['unit'] ?? 'pcs')) ?: 'pcs';

    if ($name === '' || $price < 0 || $stock < 0) {
        setFlash('danger', 'Input ditolak (BVA): Nama produk wajib diisi, harga >= 0, dan stok >= 0.');
        header('Location: /admin/products');
        exit;
    }

    $nextId = max(array_keys($_SESSION['preview']['products'])) + 1;
    $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-') ?: ('produk-' . $nextId);
    $_SESSION['preview']['products'][$nextId] = [
        'id' => $nextId,
        'umkm_merchant_id' => 1,
        'slug' => $slug,
        'name' => $name,
        'category' => $category,
        'category_key' => str_contains(strtolower($category), 'batik') ? 'batik' : (str_contains(strtolower($category), 'homestay') ? 'homestay' : 'kuliner'),
        'merchant' => 'Sentra Olahan Lorjuk Bu Maryam',
        'owner' => 'Ibu Maryam',
        'price' => $price,
        'unit' => $unit,
        'stock' => $stock,
        'low_stock_threshold' => 5,
        'is_active' => true,
        'address' => 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang',
        'description' => 'Produk baru UMKM Pantai Jumiang yang ditambahkan pada sesi demo.',
    ];
    appendAuditLog('PRODUCT_CREATE', "Tambah produk {$name} (" . rp($price) . ", stok {$stock})");
    setFlash('success', "Produk \"{$name}\" berhasil ditambahkan.");
    header('Location: /admin/products');
    exit;
}

function handleAdminInventoryPost(): void {
    $pid = (int) ($_POST['product_id'] ?? 0);
    $type = strtoupper(trim((string) ($_POST['type'] ?? 'IN')));
    $qty = (int) ($_POST['quantity'] ?? 0);
    $notes = trim((string) ($_POST['notes'] ?? 'Penyesuaian stok manual'));

    if (!isset($_SESSION['preview']['products'][$pid]) || $qty <= 0 || !in_array($type, ['IN', 'OUT', 'ADJUSTMENT'], true)) {
        setFlash('danger', 'Data mutasi stok tidak valid (kuantitas harus > 0).');
        header('Location: /admin/inventory');
        exit;
    }

    $prev = (int) $_SESSION['preview']['products'][$pid]['stock'];
    $curr = match($type) {
        'IN' => $prev + $qty,
        'OUT' => $prev - $qty,
        default => $qty,
    };

    if ($curr < 0) {
        setFlash('danger', "Stok tidak mencukupi untuk pengeluaran {$qty} (stok saat ini {$prev}).");
        header('Location: /admin/inventory');
        exit;
    }

    $_SESSION['preview']['products'][$pid]['stock'] = $curr;
    array_unshift($_SESSION['preview']['inventory_logs'], [
        'time' => date('Y-m-d H:i'),
        'product' => $_SESSION['preview']['products'][$pid]['name'],
        'type' => $type,
        'qty' => $type === 'IN' ? "+{$qty}" : ($type === 'OUT' ? "-{$qty}" : "={$qty}"),
        'prev' => $prev,
        'curr' => $curr,
        'notes' => $notes,
    ]);
    appendAuditLog('INVENTORY_ADJUST', $_SESSION['preview']['products'][$pid]['name'] . ": {$prev} -> {$curr}");
    setFlash('success', 'Mutasi stok berhasil dicatat.');
    header('Location: /admin/inventory');
    exit;
}

function handleAdminFinancialPost(): void {
    $accId = (int) ($_POST['financial_account_id'] ?? 1);
    $type = strtoupper(trim((string) ($_POST['transaction_type'] ?? 'INCOME')));
    $category = trim((string) ($_POST['category'] ?? 'Operasional Usaha'));
    $amount = (float) ($_POST['amount'] ?? 0);
    $date = trim((string) ($_POST['transaction_date'] ?? date('Y-m-d')));
    $desc = trim((string) ($_POST['description'] ?? ''));

    if (!isset($_SESSION['preview']['accounts'][$accId]) || !in_array($type, ['INCOME', 'EXPENSE'], true) || $amount <= 0 || $desc === '') {
        setFlash('danger', 'Input ditolak: Pastikan rekening valid, nominal > 0, dan deskripsi terisi.');
        header('Location: /admin/financial');
        exit;
    }

    if ($type === 'INCOME') {
        $_SESSION['preview']['accounts'][$accId]['current_balance'] += $amount;
    } else {
        $_SESSION['preview']['accounts'][$accId]['current_balance'] -= $amount;
    }

    $accLabel = $_SESSION['preview']['accounts'][$accId]['account_type'] === 'BUSINESS' ? 'Kas Usaha' : 'Rekening Pribadi';
    array_unshift($_SESSION['preview']['transactions'], [
        'date' => $date,
        'account_id' => $accId,
        'account' => $accLabel,
        'category' => $category,
        'description' => $desc,
        'type' => $type,
        'amount' => $amount,
    ]);
    appendAuditLog('FINANCIAL_ENTRY', "{$type} " . rp($amount) . " pada {$accLabel} ({$desc})");
    setFlash('success', 'Transaksi kas berhasil dicatat secara terpisah.');
    header('Location: /admin/financial');
    exit;
}

function handleAdminOrdersPost(): void {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $target = strtoupper(trim((string) ($_POST['status'] ?? '')));
    $validTransitions = [
        'PENDING' => ['PAID', 'CANCELLED'],
        'PAID' => ['PROCESSING', 'CANCELLED'],
        'PROCESSING' => ['READY_FOR_PICKUP', 'CANCELLED'],
        'READY_FOR_PICKUP' => ['COMPLETED', 'CANCELLED'],
        'COMPLETED' => [],
        'CANCELLED' => [],
    ];

    if (!isset($_SESSION['preview']['orders'][$orderId])) {
        setFlash('danger', 'Pesanan tidak ditemukan.');
        header('Location: /admin/orders');
        exit;
    }

    $current = $_SESSION['preview']['orders'][$orderId]['status'];
    if ($current === $target) {
        header('Location: /admin/orders');
        exit;
    }

    $allowed = $validTransitions[$current] ?? [];
    if (!in_array($target, $allowed, true)) {
        setFlash('danger', "Transisi FSM ditolak: Status {$current} tidak dapat berpindah langsung ke {$target}.");
        header('Location: /admin/orders');
        exit;
    }

    $_SESSION['preview']['orders'][$orderId]['status'] = $target;
    appendAuditLog('ORDER_FSM_UPDATE', $_SESSION['preview']['orders'][$orderId]['invoice'] . ": {$current} -> {$target}");
    setFlash('success', "Status pesanan berhasil diperbarui: {$current} -> {$target}.");
    header('Location: /admin/orders');
    exit;
}

function handleAdminReservationsPost(): void {
    $code = strtoupper(trim((string) ($_POST['booking_code'] ?? '')));
    foreach ($_SESSION['preview']['reservations'] as $id => $r) {
        if (strtoupper($r['code']) === $code) {
            if ($r['status'] === 'ATTENDED') {
                setFlash('warning', "Kode {$code} sudah melakukan check-in sebelumnya pada {$r['check_in']}.");
            } else {
                $_SESSION['preview']['reservations'][$id]['status'] = 'ATTENDED';
                $_SESSION['preview']['reservations'][$id]['check_in'] = date('Y-m-d H:i');
                appendAuditLog('RESERVATION_CHECKIN', "Validasi check-in tiket {$code}");
                setFlash('success', "Check-in berhasil untuk kode booking {$code} ({$r['visitors']} wisatawan).");
            }
            header('Location: /admin/reservations');
            exit;
        }
    }
    setFlash('danger', "Kode booking \"{$code}\" tidak ditemukan.");
    header('Location: /admin/reservations');
    exit;
}

function handleAdminUmkmPost(): void {
    $mid = (int) ($_POST['merchant_id'] ?? 0);
    $target = strtoupper(trim((string) ($_POST['status'] ?? '')));
    $allowedFsm = [
        'PENDING' => ['VERIFIED', 'REJECTED'],
        'VERIFIED' => ['SUSPENDED'],
        'REJECTED' => ['PENDING', 'VERIFIED'],
        'SUSPENDED' => ['VERIFIED'],
    ];

    if (!isset($_SESSION['preview']['merchants'][$mid])) {
        setFlash('danger', 'Mitra UMKM tidak ditemukan.');
        header('Location: /admin/umkm');
        exit;
    }
    $current = $_SESSION['preview']['merchants'][$mid]['verification_status'];
    if (!in_array($target, $allowedFsm[$current] ?? [], true)) {
        setFlash('danger', "Transisi verifikasi {$current} -> {$target} tidak diizinkan.");
        header('Location: /admin/umkm');
        exit;
    }

    $_SESSION['preview']['merchants'][$mid]['verification_status'] = $target;
    appendAuditLog('UMKM_VERIFY', $_SESSION['preview']['merchants'][$mid]['business_name'] . ": {$current} -> {$target}");
    setFlash('success', "Status UMKM diperbarui menjadi {$target}.");
    header('Location: /admin/umkm');
    exit;
}

function handleAdminPartnershipsPost(): void {
    $partner = trim((string) ($_POST['partner_name'] ?? ''));
    $type = trim((string) ($_POST['partnership_type'] ?? 'KOMUNITAS'));
    $contact = trim((string) ($_POST['contact_person'] ?? ''));
    $period = trim((string) ($_POST['period'] ?? '2026-2027'));

    if ($partner === '' || $contact === '') {
        setFlash('danger', 'Nama mitra dan kontak penanggung jawab wajib diisi.');
        header('Location: /admin/partnerships');
        exit;
    }

    array_unshift($_SESSION['preview']['partnerships'], [
        'partner' => $partner,
        'type' => $type,
        'contact' => $contact,
        'period' => $period,
        'status' => 'ACTIVE',
    ]);
    appendAuditLog('PARTNERSHIP_ADD', "Arsip MoU baru: {$partner}");
    setFlash('success', "Data kemitraan \"{$partner}\" berhasil diarsipkan.");
    header('Location: /admin/partnerships');
    exit;
}

function handleAdminEtlPost(): void {
    if (isset($_POST['reset_etl'])) {
        $_SESSION['preview']['custom_etl_report'] = null;
        setFlash('info', 'Tampilan ETL dikembalikan ke dataset contoh raw_umkm_survey.csv.');
        header('Location: /admin/etl');
        exit;
    }

    if (empty($_FILES['csv_file']['tmp_name']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        setFlash('danger', 'Pilih file CSV yang valid untuk diuji via pipeline ETL.');
        header('Location: /admin/etl');
        exit;
    }

    require_once __DIR__ . '/../app/Services/EtlMigrationService.php';
    try {
        $etl = new \App\Services\EtlMigrationService();
        $report = $etl->processUmkmSurveyCsv($_FILES['csv_file']['tmp_name'], null, true);
        $report['source_file'] = basename((string) ($_FILES['csv_file']['name'] ?? 'upload.csv'));
        $_SESSION['preview']['custom_etl_report'] = $report;
        appendAuditLog('ETL_DRY_RUN', "Dry-run CSV {$report['source_file']} ({$report['extracted_rows']} baris)");
        setFlash('success', "Simulasi ETL Dry-Run selesai untuk {$report['source_file']}.");
    } catch (\Throwable $th) {
        setFlash('danger', 'Gagal memproses CSV: ' . $th->getMessage());
    }

    header('Location: /admin/etl');
    exit;
}

// -------------------------------------------------------------------
// HTML Template Renderers
// -------------------------------------------------------------------

function renderLayoutHeader($title = "Jumiang Smart Tourism") {
    $safeTitle = e($title);
    $role = e(getActiveRole());
    $name = e(getUserName());
    $cartCount = count($_SESSION['preview']['cart'] ?? []);
    $flashHtml = consumeFlashHtml();

    echo <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$safeTitle} - Pantai Jumiang Pamekasan</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='30' fill='%230284c7'/%3E%3Cpath d='M12 36c6-6 12-6 18 0s12 6 22-2' stroke='white' stroke-width='5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E">
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
        .hero-banner { background-color: #0f172a; background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.88), rgba(7, 89, 133, 0.85)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80'); background-position: center; background-size: cover; color: white; padding: 70px 0; border-bottom: 4px solid var(--seafoam); }
        .btn-ocean { background: linear-gradient(135deg, #0284c7, #075985); color: white; border: none; font-weight: 600; }
        .btn-ocean:hover { background: #0369a1; color: white; }
        .card-custom { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    </style>
</head>
<body>
    <div class="bg-warning text-dark py-1 text-center small fw-semibold px-2">
        <i class="bi bi-info-circle-fill me-1"></i> [DATA DEMO / SIMULASI] Jumiang Smart Tourism & UMKM - Acuan Akademik RPS MANPRO (16 Pertemuan)
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-marine sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 brand-font fw-bold fs-4" href="/">
                <i class="bi bi-water text-info fs-3"></i>
                <span>JUMIANG <span class="text-info">SMART TOURISM</span></span>
            </a>
            <button class="navbar-toggler border-info" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2 pt-3 pt-lg-0">
                    <li class="nav-item"><a class="nav-link text-white" href="/"><i class="bi bi-house-door me-1"></i> Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/tentang-jumiang"><i class="bi bi-compass me-1"></i> Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/peta-wisata"><i class="bi bi-geo-alt me-1"></i> Peta GIS</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/katalog-umkm"><i class="bi bi-shop me-1"></i> Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/reservasi-wisata"><i class="bi bi-ticket-perforated me-1"></i> Tiket</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/keranjang" title="Keranjang Belanja"><i class="bi bi-cart3"></i> <span class="badge bg-danger rounded-pill">{$cartCount}</span></a></li>
                    <li class="nav-item ms-lg-2 d-flex align-items-center gap-2">
                        <a class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-dark" href="/login" title="{$name} ({$role})">
                            <i class="bi bi-person-circle me-1"></i> Panel Multi-Role
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    {$flashHtml}
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
    $umkmCount = count(array_filter($_SESSION['preview']['merchants'], fn ($m) => $m['verification_status'] === 'VERIFIED'));
    $prodCount = count($_SESSION['preview']['products']);
    echo <<<HTML
    <section class="hero-banner text-center text-lg-start">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3"><i class="bi bi-geo-alt-fill"></i> PADEMAWU, PAMEKASAN, MADURA</span>
                    <h1 class="display-4 fw-extrabold text-white mb-3 leading-tight">
                        Pesona Bahari Tebing &amp; <br><span class="text-info">Ekosistem UMKM Jumiang</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        Platform terintegrasi informasi wisata, peta geospasial interaktif, etalase olahan lorjuk &amp; batik pesisir, dan pembukuan keuangan mandiri pedagang lokal.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
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
                        <p class="small mb-3"><strong>Komoditas Unggulan:</strong> Olahan Lorjuk, Ikan Teri Krispi, Terasi Tanjung, Batik Pesisir</p>
                        <a href="/reservasi-wisata" class="btn btn-info text-dark fw-bold rounded-pill w-100">Pesan Tiket Wisata Online</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white border-bottom">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-md-3 col-6"><h3 class="fw-bold text-primary">4 Spot</h3><small class="text-muted">Tebing &amp; Pantai</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-success">{$umkmCount} Gerai</h3><small class="text-muted">UMKM Terverifikasi ({$prodCount} Produk)</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-info">7 Marker</h3><small class="text-muted">Peta Spasial WGS84</small></div>
                <div class="col-md-3 col-6"><h3 class="fw-bold text-warning">Terpisah</h3><small class="text-muted">Arus Kas Usaha &amp; Pribadi</small></div>
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
            <h5 class="fw-bold text-dark">Karakteristik &amp; Potensi Bahari</h5>
            <p class="text-muted">Pantai Jumiang memiliki ciri khas bebatuan karang tebing yang menjorok ke Selat Madura dan area pantai landai dengan ombak bersahabat. Kawasan ini merupakan sentra komoditas lorjuk (kerang bambu) segar yang menjadi mata pencaharian puluhan warga pesisir.</p>
            <div class="row g-3 pt-2">
                <div class="col-md-4"><small class="text-muted d-block">Jam Operasional</small><strong>06:00 - 18:00 WIB</strong></div>
                <div class="col-md-4"><small class="text-muted d-block">Tiket Masuk [DEMO]</small><strong>Rp 10.000 / wisatawan</strong></div>
                <div class="col-md-4"><small class="text-muted d-block">Fasilitas Umum</small><strong>Musholla, Toilet Bilas, Gazebo, Parkir Bus</strong></div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3"><div class="card card-custom p-3 h-100"><span class="badge bg-warning text-dark align-self-start mb-2">Spot Ikonik</span><h6>Puncak Tebing Jumiang</h6><small class="text-muted">Spot foto pemandangan laut 180 derajat dari atas tebing karang tinggi.</small></div></div>
            <div class="col-md-3"><div class="card card-custom p-3 h-100"><span class="badge bg-success align-self-start mb-2">Keluarga</span><h6>Pantai Pasir Landai</h6><small class="text-muted">Area rekreasi keluarga yang aman dan nyaman untuk anak-anak.</small></div></div>
            <div class="col-md-3"><div class="card card-custom p-3 h-100"><span class="badge bg-info text-dark align-self-start mb-2">Panorama</span><h6>Sunset Point Jumiang</h6><small class="text-muted">Pemandangan matahari terbenam dengan siluet perahu nelayan.</small></div></div>
            <div class="col-md-3"><div class="card card-custom p-3 h-100"><span class="badge bg-primary align-self-start mb-2">Edukasi</span><h6>Hutan Mangrove Pesisir</h6><small class="text-muted">Jalur treking kayu pelindung ekosistem pesisir Pantai Jumiang.</small></div></div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderPublicMap() {
    $category = strtoupper(trim((string) ($_GET['category'] ?? 'ALL')));
    $allMarkers = [
        ['lat' => -7.228912, 'lng' => 113.538120, 'title' => 'Puncak Tebing Karang Jumiang', 'cat' => 'WISATA', 'desc' => 'Spot pemandangan laut dari atas tebing karang tinggi.'],
        ['lat' => -7.229450, 'lng' => 113.537540, 'title' => 'Sunset Point Pantai Jumiang', 'cat' => 'WISATA', 'desc' => 'Titik menikmati matahari terbenam terbaik di tebing selatan.'],
        ['lat' => -7.227800, 'lng' => 113.536900, 'title' => 'Sentra Olahan Lorjuk Bu Maryam', 'cat' => 'UMKM', 'desc' => 'Pusat oleh-oleh rengginang lorjuk, keripik ikan, dan terasi khas Jumiang.'],
        ['lat' => -7.227200, 'lng' => 113.536100, 'title' => 'Galeri Batik Pesisir Tanjung', 'cat' => 'UMKM', 'desc' => 'Kain batik tulis corak bahari khas nelayan Desa Tanjung.'],
        ['lat' => -7.226500, 'lng' => 113.535800, 'title' => 'Homestay Bahari Bu Sugiarti', 'cat' => 'HOMESTAY', 'desc' => 'Penginapan tepi pantai berfasilitas lengkap.'],
        ['lat' => -7.228100, 'lng' => 113.537300, 'title' => 'Musholla Nurul Bahari & Toilet', 'cat' => 'FASILITAS', 'desc' => 'Fasilitas ibadah dan kamar bilas air tawar.'],
        ['lat' => -7.226900, 'lng' => 113.536500, 'title' => 'Area Parkir Utama Wisata', 'cat' => 'PARKIR', 'desc' => 'Kapasitas muat 50 mobil dan 200 sepeda motor.'],
    ];

    $markers = ($category === '' || $category === 'ALL')
        ? $allMarkers
        : array_values(array_filter($allMarkers, fn ($m) => $m['cat'] === $category));
    if (empty($markers) && $category !== 'ALL') {
        $category = 'ALL';
        $markers = $allMarkers;
    }

    $markersJson = json_encode($markers, JSON_UNESCAPED_UNICODE);
    $countMarkers = count($markers);
    $tabs = [
        'ALL' => 'Semua Titik (7)',
        'WISATA' => 'Spot Wisata',
        'UMKM' => 'Gerai UMKM',
        'FASILITAS' => 'Fasilitas',
        'HOMESTAY' => 'Homestay',
        'PARKIR' => 'Parkir',
    ];
    $tabButtons = '';
    foreach ($tabs as $key => $label) {
        $activeClass = $category === $key ? 'btn-primary text-white' : 'btn-outline-secondary';
        $tabButtons .= "<a href='/peta-wisata?category=" . urlencode($key) . "' class='btn btn-sm {$activeClass} rounded-pill px-3'>{$label}</a>\n";
    }

    renderLayoutHeader("Peta Wisata Digital (GIS)");
    echo <<<HTML
    <div class="container-fluid px-3 py-3">
        <div class="card card-custom overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <div class="row align-items-center g-3">
                    <div class="col-md-5">
                        <h4 class="fw-bold text-primary brand-font mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i>PETA INTERAKTIF PANTAI JUMIANG (LEAFLET.JS)</h4>
                        <span class="badge bg-primary mt-1">WGS84 Datum</span>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            {$tabButtons}
                        </div>
                    </div>
                </div>
            </div>
            <div id="map" style="height: 560px; width: 100%; z-index: 1;"></div>
            <div class="card-footer bg-light py-2 px-4 d-flex flex-wrap justify-content-between align-items-center small text-secondary">
                <div>
                    <span class="me-3"><i class="bi bi-bullseye text-primary"></i> Pusat: -7.228912, 113.538120</span>
                    <span><i class="bi bi-pin-map text-success"></i> Desa Tanjung, Kec. Pademawu, Pamekasan</span>
                </div>
                <span class="badge bg-secondary">Menampilkan {$countMarkers} Marker Aktif</span>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([-7.228912, 113.538120], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var colorMap = {
                'WISATA': '#0284c7',
                'UMKM': '#16a34a',
                'FASILITAS': '#06b6d4',
                'HOMESTAY': '#ea580c',
                'PARKIR': '#475569'
            };

            var markers = {$markersJson};
            markers.forEach(function(m) {
                var fill = colorMap[m.cat] || '#dc2626';
                var c = L.circleMarker([m.lat, m.lng], {
                    radius: 10,
                    fillColor: fill,
                    color: "#ffffff",
                    weight: 2,
                    fillOpacity: 0.9
                }).addTo(map);
                c.bindPopup(
                    "<div style='min-width:180px;'>" +
                    "<span style='background:" + fill + ";color:#fff;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:bold;'>" + m.cat + "</span>" +
                    "<h6 style='margin:6px 0 3px;font-weight:bold;'>" + m.title + "</h6>" +
                    "<p style='font-size:12px;color:#475569;margin:0 0 4px;'>" + m.desc + "</p>" +
                    "<small style='color:#94a3b8;'>Koordinat: " + m.lat.toFixed(6) + ", " + m.lng.toFixed(6) + "</small></div>"
                );
            });
        });
    </script>
HTML;
    renderLayoutFooter();
}

function renderPublicCatalog() {
    $search = trim((string) ($_GET['search'] ?? $_GET['q'] ?? ''));
    $catFilter = strtolower(trim((string) ($_GET['category'] ?? 'all')));
    $safeSearch = e($search);
    $searchBanner = $search !== ''
        ? "<div class='alert alert-info py-2 small'>Hasil pencarian untuk: <strong>{$safeSearch}</strong> <a href='/katalog-umkm' class='ms-2 text-decoration-underline'>Reset</a></div>"
        : '';

    $products = array_values($_SESSION['preview']['products']);
    $filtered = array_values(array_filter($products, function ($p) use ($search, $catFilter) {
        if ($catFilter !== '' && $catFilter !== 'all' && $p['category_key'] !== $catFilter) {
            return false;
        }
        if ($search !== '') {
            $haystack = strtolower($p['name'] . ' ' . $p['merchant'] . ' ' . $p['category'] . ' ' . $p['description']);
            if (!str_contains($haystack, strtolower($search))) {
                return false;
            }
        }
        return true;
    }));

    $catPills = '';
    foreach (['all' => 'Semua Kategori', 'kuliner' => 'Kuliner Laut', 'batik' => 'Batik Pesisir', 'homestay' => 'Homestay'] as $key => $label) {
        $cls = $catFilter === $key ? 'btn-primary text-white' : 'btn-outline-secondary';
        $catPills .= "<a href='/katalog-umkm?category=" . urlencode($key) . "' class='btn btn-sm {$cls} rounded-pill px-3'>{$label}</a> ";
    }

    $cardsHtml = '';
    foreach ($filtered as $p) {
        $pId = (int) $p['id'];
        $pSlug = urlencode($p['slug']);
        $pName = e($p['name']);
        $pCat = e($p['category']);
        $pMerchant = e($p['merchant']);
        $pPrice = rp($p['price']);
        $pStock = (int) $p['stock'];
        $pUnit = e($p['unit']);
        $icon = $p['category_key'] === 'batik' ? 'bi-palette' : ($p['category_key'] === 'homestay' ? 'bi-house-door' : 'bi-box-seam');
        $disabled = $pStock <= 0 ? 'disabled' : '';
        $cardsHtml .= <<<HTML
            <div class="col-md-4">
                <div class="card card-custom p-3 h-100">
                    <div class="bg-light p-4 text-center rounded mb-3"><i class="bi {$icon} display-4 text-primary"></i></div>
                    <span class="badge bg-info text-dark align-self-start mb-2">{$pCat}</span>
                    <h5 class="fw-bold mb-1"><a href="/produk/{$pSlug}" class="text-decoration-none text-dark">{$pName}</a></h5>
                    <small class="text-muted mb-2">{$pMerchant} &bull; Stok: {$pStock} {$pUnit}</small>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <strong class="text-primary fs-5">{$pPrice}</strong>
                        <form action="/keranjang" method="POST" class="m-0">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="{$pId}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-ocean btn-sm rounded-pill px-3" {$disabled}>+ Keranjang</button>
                        </form>
                    </div>
                </div>
            </div>
HTML;
    }

    if ($cardsHtml === '') {
        $cardsHtml = "<div class='col-12'><div class='card card-custom p-5 text-center text-muted'><h5>Tidak ada produk yang cocok dengan pencarian Anda.</h5><a href='/katalog-umkm' class='btn btn-outline-primary btn-sm rounded-pill mt-2'>Tampilkan Semua Produk</a></div></div>";
    }

    renderLayoutHeader("Marketplace Produk UMKM");
    echo <<<HTML
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-primary brand-font mb-1">Etalase Digital Produk UMKM Jumiang</h2>
                <div class="d-flex flex-wrap gap-2 mt-2">{$catPills}</div>
            </div>
            <div class="d-flex flex-column align-items-md-end gap-2">
                <span class="badge bg-warning text-dark"><i class="bi bi-shield-check"></i> TRANSAKSI SIMULASI</span>
                <form action="/katalog-umkm" method="GET" class="input-group input-group-sm" style="min-width: 260px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari lorjuk, batik, homestay..." value="{$safeSearch}">
                    <button type="submit" class="btn btn-ocean"><i class="bi bi-search"></i> Cari</button>
                </form>
            </div>
        </div>
        {$searchBanner}
        <div class="row g-4">
            {$cardsHtml}
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderProductDetail() {
    global $uri;
    $slug = trim(urldecode(substr($uri, strlen('/produk/'))), '/');
    $products = $_SESSION['preview']['products'];
    $found = null;
    foreach ($products as $p) {
        if ($p['slug'] === $slug || str_starts_with($slug, $p['slug'])) {
            $found = $p;
            break;
        }
    }

    $item = $found ?? [
        'id' => 1,
        'name' => ucwords(str_replace('-', ' ', $slug ?: 'Produk UMKM Jumiang')),
        'category' => 'Produk UMKM Pantai Jumiang',
        'merchant' => 'Mitra UMKM Pantai Jumiang',
        'owner' => 'Pelaku UMKM Lokal',
        'price' => 35000,
        'unit' => 'pcs',
        'stock' => 15,
        'address' => 'Kawasan Wisata Pantai Jumiang, Desa Tanjung',
        'description' => 'Produk olahan berkualitas khas Pantai Jumiang, diproduksi langsung oleh pelaku UMKM lokal Desa Tanjung secara higienis dan terjamin.'
    ];

    $safeName = e($item['name']);
    $safeCategory = e($item['category']);
    $safeMerchant = e($item['merchant']);
    $safeOwner = e($item['owner']);
    $safeAddress = e($item['address']);
    $safeDesc = e($item['description']);
    $safeUnit = e($item['unit']);
    $pPrice = rp($item['price']);
    $pId = (int) $item['id'];
    $pStock = (int) $item['stock'];

    renderLayoutHeader($item['name'] . ' - Jumiang UMKM');
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
                        <span class="display-6 fw-bold text-primary">{$pPrice}</span>
                        <span class="text-muted">/ {$safeUnit}</span>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                        <p class="text-secondary">{$safeDesc}</p>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6"><div class="p-3 border rounded-3"><span class="small text-muted d-block">Stok Tersedia</span><span class="fw-bold fs-5 text-success">{$pStock} {$safeUnit}</span></div></div>
                        <div class="col-sm-6"><div class="p-3 border rounded-3"><span class="small text-muted d-block">Lokasi Gerai</span><span class="fw-bold small text-dark d-block text-truncate">{$safeAddress}</span></div></div>
                    </div>
                    <form action="/keranjang" method="POST" class="d-flex gap-2">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="{$pId}">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{$pStock}" style="width:90px;">
                        <button type="submit" class="btn btn-ocean btn-lg flex-grow-1 rounded-pill fw-bold"><i class="bi bi-cart3 me-2"></i> Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderCart() {
    renderLayoutHeader("Keranjang Belanja");
    $cart = $_SESSION['preview']['cart'] ?? [];
    $products = $_SESSION['preview']['products'];
    $rowsHtml = '';
    $subtotal = 0;

    foreach ($cart as $pid => $qty) {
        if (!isset($products[$pid])) {
            continue;
        }
        $p = $products[$pid];
        $lineTotal = $p['price'] * $qty;
        $subtotal += $lineTotal;
        $pName = e($p['name']);
        $pMerchant = e($p['merchant']);
        $pUnit = e($p['unit']);
        $pPrice = rp($p['price']);
        $pLine = rp($lineTotal);
        $pStock = (int) $p['stock'];
        $rowsHtml .= <<<HTML
            <tr>
                <td>
                    <strong>{$pName}</strong>
                    <small class="d-block text-muted">{$pMerchant}</small>
                </td>
                <td>{$pPrice}</td>
                <td>
                    <div class="input-group input-group-sm" style="width:120px;">
                        <input type="number" name="quantities[{$pid}]" class="form-control text-center" value="{$qty}" min="0" max="{$pStock}">
                        <span class="input-group-text">{$pUnit}</span>
                    </div>
                </td>
                <td class="fw-bold text-primary">{$pLine}</td>
                <td>
                    <button type="submit" name="action" value="remove" onclick="this.form.product_id.value={$pid}" class="btn btn-outline-danger btn-sm" title="Hapus item"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
HTML;
    }

    $totalRp = rp($subtotal);
    $checkoutBtn = $subtotal > 0
        ? "<a href='/checkout' class='btn btn-ocean w-100 rounded-pill fw-bold'>Lanjut ke Checkout</a>"
        : "<a href='/katalog-umkm' class='btn btn-outline-primary w-100 rounded-pill fw-bold'>Belanja di Katalog</a>";

    if ($rowsHtml === '') {
        $rowsHtml = "<tr><td colspan='5' class='text-center text-muted py-4'>Keranjang Anda kosong. Silakan pilih produk dari Katalog UMKM.</td></tr>";
    }

    echo <<<HTML
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary brand-font mb-0">Keranjang Belanja Anda</h3>
            <a href="/katalog-umkm" class="btn btn-outline-secondary btn-sm rounded-pill">+ Tambah Produk Lain</a>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-custom p-4">
                    <form action="/keranjang" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="product_id" value="0">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Total</th><th></th></tr></thead>
                                <tbody>{$rowsHtml}</tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="action" value="update" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="bi bi-arrow-repeat me-1"></i> Perbarui Jumlah</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3">Total Pesanan: <span class="text-primary">{$totalRp}</span></h5>
                    <p class="small text-muted mb-3">Pengurangan stok dan jurnal kas UMKM berjalan otomatis saat konfirmasi pembayaran simulasi.</p>
                    {$checkoutBtn}
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderCheckout() {
    renderLayoutHeader("Checkout Pesanan");
    $invoiceParam = trim((string) ($_GET['invoice'] ?? ''));
    $lastOrder = $_SESSION['preview']['last_order'] ?? null;

    if ($invoiceParam !== '' && is_array($lastOrder) && $lastOrder['invoice'] === $invoiceParam) {
        $inv = e($lastOrder['invoice']);
        $itemsTxt = e($lastOrder['items_text']);
        $method = e($lastOrder['method']);
        $tot = rp($lastOrder['total']);
        echo <<<HTML
        <div class="container py-5">
            <div class="card card-custom p-5 mx-auto text-center" style="max-width: 650px;">
                <i class="bi bi-check-circle-fill display-3 text-success mb-3"></i>
                <span class="badge bg-success align-self-center mb-2 px-3 py-2">PEMBAYARAN SIMULASI LUNAS (PAID)</span>
                <h3 class="fw-bold text-dark mb-2">Pesanan Berhasil Dibuat!</h3>
                <p class="text-muted mb-3">Kode Invoice: <strong class="text-primary">{$inv}</strong></p>
                <div class="p-3 bg-light rounded text-start small mb-4">
                    <div><strong>Detail Item:</strong> {$itemsTxt}</div>
                    <div><strong>Metode Gateway:</strong> {$method}</div>
                    <div><strong>Total Dibayar:</strong> <strong class="text-primary">{$tot}</strong></div>
                    <div class="text-success mt-1"><i class="bi bi-journal-check"></i> Stok produk otomatis berkurang &amp; kas <code>BUSINESS</code> merchant bertambah.</div>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="/dashboard" class="btn btn-ocean rounded-pill px-4">Lihat Dashboard</a>
                    <a href="/katalog-umkm" class="btn btn-outline-primary rounded-pill px-4">Belanja Lagi</a>
                </div>
            </div>
        </div>
HTML;
        renderLayoutFooter();
        return;
    }

    $cart = $_SESSION['preview']['cart'] ?? [];
    $products = $_SESSION['preview']['products'];
    $total = 0;
    $summary = [];
    foreach ($cart as $pid => $qty) {
        if (isset($products[$pid])) {
            $total += $products[$pid]['price'] * $qty;
            $summary[] = "{$qty}x " . e($products[$pid]['name']);
        }
    }

    if ($total <= 0) {
        echo <<<HTML
        <div class="container py-5">
            <div class="card card-custom p-5 mx-auto text-center" style="max-width: 600px;">
                <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
                <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Kosong</h4>
                <p class="text-muted small mb-4">Tambahkan produk UMKM Pantai Jumiang terlebih dahulu sebelum melanjutkan ke simulasi pembayaran.</p>
                <a href="/katalog-umkm" class="btn btn-ocean rounded-pill px-4 align-self-center">Jelajahi Katalog UMKM</a>
            </div>
        </div>
HTML;
        renderLayoutFooter();
        return;
    }

    $totalRp = rp($total);
    $summaryStr = implode(', ', $summary);

    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 mx-auto" style="max-width: 650px;">
            <h3 class="fw-bold text-primary mb-3">Simulasi Checkout Pesanan</h3>
            <div class="p-3 bg-light rounded mb-4">
                <span class="badge bg-warning text-dark mb-2">SIMULASI QRIS / VA</span>
                <p class="small text-muted mb-0">Total: <strong>{$totalRp}</strong> ({$summaryStr})</p>
            </div>
            <form action="/checkout" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Metode Pembayaran Simulasi</label>
                    <select name="payment_method" class="form-select">
                        <option value="SIMULATION_QRIS">QRIS Jumiang (Simulasi Instan)</option>
                        <option value="SIMULATION_VA">Virtual Account Bank Daerah (Simulasi)</option>
                        <option value="CASH_ON_LOCATION">Bayar Tunai di Gerai Pantai</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Opsi Pengambilan</label>
                    <select name="delivery_option" class="form-select">
                        <option value="PICKUP">Ambil Langsung di Gerai UMKM Pantai</option>
                        <option value="DELIVERY">Antar ke Homestay / Penginapan Sekitar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold">Konfirmasi Pembayaran Simulasi Lunas</button>
            </form>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderReservation() {
    renderLayoutHeader("Reservasi Wisata");
    $bookedCode = trim((string) ($_GET['booked'] ?? ''));
    $lastRes = $_SESSION['preview']['last_reservation'] ?? null;
    $confirmHtml = '';

    if ($bookedCode !== '' && is_array($lastRes) && $lastRes['code'] === $bookedCode) {
        $c = e($lastRes['code']);
        $d = e($lastRes['date']);
        $p = e($lastRes['package']);
        $v = (int) $lastRes['visitors'];
        $t = rp($lastRes['total']);
        $confirmHtml = <<<HTML
            <div class="alert alert-success mb-4">
                <h6 class="fw-bold mb-1"><i class="bi bi-ticket-detailed me-1"></i> E-Tiket Reservasi Diterbitkan!</h6>
                <div class="small">Kode Booking: <strong>{$c}</strong> | Tanggal: {$d} | {$p} ({$v} orang) | Total: <strong>{$t}</strong></div>
            </div>
HTML;
    }

    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-4 mx-auto" style="max-width: 600px;">
            <h3 class="fw-bold text-primary mb-3">Reservasi Tiket Pantai Jumiang</h3>
            <p class="small text-muted mb-3">Tarif simulasi tiket masuk: <strong>Rp 10.000 / orang</strong></p>
            {$confirmHtml}
            <form action="/reservasi-wisata" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Paket Kunjungan</label>
                    <select name="package_name" class="form-select">
                        <option value="Tiket Masuk Reguler">Tiket Masuk Reguler (Rp 10.000/org)</option>
                        <option value="Paket Wisata Keluarga & Tebing">Paket Wisata Keluarga &amp; Tebing</option>
                        <option value="Paket Rombongan Edukasi Mangrove">Paket Rombongan Edukasi Mangrove</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Wisatawan</label>
                    <input type="number" name="visitor_count" class="form-control" value="2" min="1" max="500" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Kunjungan</label>
                    <input type="date" name="visit_date" class="form-control" value="2026-11-15" required>
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
        <div class="card card-custom p-4 p-md-5 mx-auto" style="max-width: 500px;">
            <h3 class="fw-bold text-center brand-font mb-2">Masuk ke Sistem</h3>
            <p class="small text-muted text-center mb-4">Akses Multi-Role Terintegrasi Jumiang ERP</p>

            <div class="p-3 bg-light rounded-3 border mb-4">
                <span class="badge bg-warning text-dark mb-2 small fw-bold">AKUN DEMO CEPAT (KLIK UNTUK MASUK):</span>
                <div class="d-grid gap-2">
                    <a href="/demo-login?role=super_admin" class="btn btn-outline-primary btn-sm text-start"><strong>1. Super Admin</strong> (Kontrol Penuh 14 Modul &amp; ETL)</a>
                    <a href="/demo-login?role=pengelola_wisata" class="btn btn-outline-info btn-sm text-start text-dark"><strong>2. Pengelola Wisata</strong> (Tiket &amp; Check-In)</a>
                    <a href="/demo-login?role=pelaku_umkm" class="btn btn-outline-success btn-sm text-start"><strong>3. Pelaku UMKM</strong> (Produk, Stok &amp; Kas Mandiri)</a>
                    <a href="/demo-login?role=bendahara" class="btn btn-outline-secondary btn-sm text-start"><strong>4. Bendahara</strong> (Arus Kas Kawasan &amp; Laporan)</a>
                    <a href="/demo-login?role=pimpinan" class="btn btn-outline-dark btn-sm text-start"><strong>5. Pimpinan / EIS</strong> (Analitik Strategis)</a>
                    <a href="/demo-login?role=wisatawan" class="btn btn-outline-warning btn-sm text-start text-dark"><strong>6. Wisatawan</strong> (Belanja, Peta &amp; Tiket)</a>
                </div>
            </div>

            <form action="/login" method="POST" class="mb-3">
                <div class="mb-2">
                    <label class="form-label small fw-bold">Atau Login Email Demo (@jumiang.test)</label>
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="admin@jumiang.test / umkm.maryam@jumiang.test" required>
                </div>
                <button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">Masuk dengan Email</button>
            </form>

            <div class="d-flex justify-content-between small">
                <a href="/register">Daftar Akun Wisatawan Baru</a>
                <a href="/">Kembali ke Beranda</a>
            </div>
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
            <h3 class="fw-bold text-center mb-2">Daftar Akun Wisatawan</h3>
            <p class="small text-muted text-center mb-4">Registrasi akun simulasi pengunjung Pantai Jumiang</p>
            <form action="/register" method="POST">
                <div class="mb-3"><label class="form-label fw-bold small">Nama Lengkap</label><input type="text" name="name" class="form-control" placeholder="Budi Santoso" required></div>
                <div class="mb-3"><label class="form-label fw-bold small">Alamat Email</label><input type="email" name="email" class="form-control" placeholder="budi@test.com" required></div>
                <div class="mb-3"><label class="form-label fw-bold small">Password (Min. 6 Karakter)</label><input type="password" name="password" class="form-control" minlength="6" placeholder="******" required></div>
                <button type="submit" class="btn btn-ocean w-100 rounded-pill fw-bold">Daftar Sekarang</button>
            </form>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderDashboard() {
    $role = getActiveRole();
    $safeRole = e($role);
    $safeName = e(getUserName());
    renderLayoutHeader("Dashboard - " . ucfirst($role));

    $orders = $_SESSION['preview']['orders'];
    $merchants = $_SESSION['preview']['merchants'];
    $reservations = $_SESSION['preview']['reservations'];
    $totalRev = 3000000 + array_sum(array_column($orders, 'total'));
    $totalVisitors = array_sum(array_column($reservations, 'visitors'));
    $totalRevFormatted = rp($totalRev);
    $totalOrders = count($orders);
    $totalUmkm = count(array_filter($merchants, fn ($m) => $m['verification_status'] === 'VERIFIED'));

    // Role-scoped quick navigation buttons (prevents Wisatawan clicking guaranteed-403 links in UI, while route RBAC still enforces 403)
    $buttons = [];
    if (in_array($role, ['super_admin', 'pelaku_umkm'], true)) {
        $buttons[] = "<a href='/admin/products' class='btn btn-sm btn-outline-primary rounded-pill'>Produk UMKM</a>";
        $buttons[] = "<a href='/admin/inventory' class='btn btn-sm btn-outline-primary rounded-pill'>Inventori &amp; Stok</a>";
    }
    if (in_array($role, ['super_admin', 'pelaku_umkm', 'bendahara'], true)) {
        $buttons[] = "<a href='/admin/financial' class='btn btn-sm btn-outline-success rounded-pill'>Buku Kas Terpisah</a>";
        $buttons[] = "<a href='/admin/financial/profit-loss' class='btn btn-sm btn-outline-success rounded-pill'>Laba Rugi</a>";
    }
    if (in_array($role, ['super_admin', 'pelaku_umkm', 'pengelola_wisata'], true)) {
        $buttons[] = "<a href='/admin/orders' class='btn btn-sm btn-outline-info rounded-pill text-dark'>Pesanan Masuk</a>";
    }
    if (in_array($role, ['super_admin', 'pengelola_wisata'], true)) {
        $buttons[] = "<a href='/admin/reservations' class='btn btn-sm btn-outline-warning rounded-pill text-dark'>Tiket &amp; Check-in</a>";
    }
    if ($role === 'super_admin') {
        $buttons[] = "<a href='/admin/umkm' class='btn btn-sm btn-outline-dark rounded-pill'>Verifikasi Mitra</a>";
        $buttons[] = "<a href='/admin/partnerships' class='btn btn-sm btn-outline-dark rounded-pill'>Kemitraan (MoU)</a>";
    }
    if (in_array($role, ['super_admin', 'pimpinan', 'bendahara'], true)) {
        $buttons[] = "<a href='/admin/eis' class='btn btn-sm btn-primary rounded-pill'>EIS Analytics</a>";
        $buttons[] = "<a href='/admin/reports' class='btn btn-sm btn-secondary rounded-pill'>Laporan</a>";
    }
    if ($role === 'super_admin') {
        $buttons[] = "<a href='/admin/audit-logs' class='btn btn-sm btn-secondary rounded-pill'>Audit Trail</a>";
        $buttons[] = "<a href='/admin/etl' class='btn btn-sm btn-outline-dark rounded-pill'>ETL Import/Export CSV</a>";
    }
    if ($role === 'wisatawan') {
        $buttons[] = "<a href='/peta-wisata' class='btn btn-sm btn-outline-primary rounded-pill'>Peta Digital GIS</a>";
        $buttons[] = "<a href='/katalog-umkm' class='btn btn-sm btn-outline-success rounded-pill'>Belanja Marketplace</a>";
        $buttons[] = "<a href='/keranjang' class='btn btn-sm btn-outline-info rounded-pill text-dark'>Keranjang Belanja</a>";
        $buttons[] = "<a href='/reservasi-wisata' class='btn btn-sm btn-outline-warning rounded-pill text-dark'>Pesan Tiket Wisata</a>";
    }

    $quickMenuHtml = implode("\n", $buttons);

    echo <<<HTML
    <div class="container-fluid py-4 px-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h3 class="fw-bold text-dark brand-font mb-0">Panel Dashboard: {$safeName}</h3>
                <span class="badge bg-primary px-3 py-1 text-uppercase">Role Aktif: {$safeRole}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="/login" class="btn btn-outline-primary btn-sm rounded-pill px-3">Ganti Role Demo</a>
                <a href="/logout" class="btn btn-outline-danger btn-sm rounded-pill px-3">Keluar Sistem</a>
            </div>
        </div>

        <div class="card card-custom p-3 bg-white mb-4">
            <div class="d-flex flex-wrap gap-2">
                {$quickMenuHtml}
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Omzet</small><h4 class="fw-bold text-primary mb-0">{$totalRevFormatted}</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-4 bg-white"><small class="text-muted">Mitra UMKM</small><h4 class="fw-bold text-success mb-0">{$totalUmkm} Unit</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Transaksi</small><h4 class="fw-bold text-info mb-0">{$totalOrders} Pesanan</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-4 bg-white"><small class="text-muted">Pengunjung</small><h4 class="fw-bold text-warning mb-0">{$totalVisitors} Wisatawan</h4></div></div>
        </div>

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
                        data: [1200000, 1850000, 2400000, 2100000, 3100000, {$totalRev}],
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
    $rows = '';
    foreach ($_SESSION['preview']['products'] as $p) {
        $badgeCls = $p['stock'] <= $p['low_stock_threshold'] ? 'bg-danger' : 'bg-success';
        $lowNote = $p['stock'] <= $p['low_stock_threshold'] ? ' (Menipis)' : '';
        $rows .= "<tr><td><strong>" . e($p['name']) . "</strong></td><td>" . e($p['category']) . "</td><td>" . rp($p['price']) . "</td><td><span class='badge {$badgeCls}'>" . (int) $p['stock'] . ' ' . e($p['unit']) . "{$lowNote}</span></td><td><span class='badge bg-primary'>Aktif</span></td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Manajemen Produk UMKM</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali ke Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3">Tambah Produk Baru (Simulasi Validasi BVA Harga &ge; 0)</h6>
            <form action="/admin/products" method="POST" class="row g-2 align-items-end">
                <div class="col-md-3"><label class="form-label small fw-bold">Nama Produk</label><input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Petis Ikan Madura" required></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Kategori</label><select name="category" class="form-select form-select-sm"><option>Kuliner &amp; Oleh-Oleh Laut</option><option>Batik &amp; Busana Lokal</option><option>Penginapan &amp; Homestay</option></select></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Harga (Rp)</label><input type="number" name="price" class="form-control form-control-sm" min="0" value="25000" required></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Stok</label><input type="number" name="stock" class="form-control form-control-sm" min="0" value="20" required></div>
                <div class="col-md-2"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">+ Simpan Produk</button></div>
            </form>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminInventory() {
    renderLayoutHeader("Mutasi Inventori");
    $prodOpts = '';
    foreach ($_SESSION['preview']['products'] as $id => $p) {
        $prodOpts .= "<option value='{$id}'>" . e($p['name']) . " (Stok: " . (int) $p['stock'] . ")</option>";
    }
    $rows = '';
    foreach ($_SESSION['preview']['inventory_logs'] as $log) {
        $badge = $log['type'] === 'IN' ? 'bg-success' : ($log['type'] === 'OUT' ? 'bg-danger' : 'bg-warning text-dark');
        $rows .= "<tr><td>" . e($log['time']) . "</td><td>" . e($log['product']) . "</td><td><span class='badge {$badge}'>" . e($log['type']) . "</span></td><td>" . e($log['qty']) . "</td><td class='fw-bold'>" . (int) $log['curr'] . "</td><td>" . e($log['notes']) . "</td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Buku Mutasi Inventori &amp; Stok</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3">Catat Mutasi Stok Manual</h6>
            <form action="/admin/inventory" method="POST" class="row g-2 align-items-end">
                <div class="col-md-4"><label class="form-label small fw-bold">Produk</label><select name="product_id" class="form-select form-select-sm">{$prodOpts}</select></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Tipe</label><select name="type" class="form-select form-select-sm"><option value="IN">IN (Masuk)</option><option value="OUT">OUT (Keluar)</option><option value="ADJUSTMENT">ADJUSTMENT</option></select></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Jumlah</label><input type="number" name="quantity" class="form-control form-control-sm" min="1" value="5" required></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Catatan</label><input type="text" name="notes" class="form-control form-control-sm" placeholder="Restok harian" required></div>
                <div class="col-md-2"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">Catat Mutasi</button></div>
            </form>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead><tr><th>Waktu</th><th>Produk</th><th>Tipe</th><th>Jumlah</th><th>Stok Akhir</th><th>Keterangan</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminFinancial() {
    renderLayoutHeader("Buku Kas Mandiri");
    $acc1 = $_SESSION['preview']['accounts'][1];
    $acc2 = $_SESSION['preview']['accounts'][2];
    $bal1 = rp($acc1['current_balance']);
    $bal2 = rp($acc2['current_balance']);
    $rows = '';
    foreach ($_SESSION['preview']['transactions'] as $tx) {
        $isIncome = $tx['type'] === 'INCOME';
        $badge = $isIncome ? 'bg-success' : 'bg-danger';
        $txtCls = $isIncome ? 'text-success' : 'text-danger';
        $sign = $isIncome ? '+' : '-';
        $rows .= "<tr><td>" . e($tx['date']) . "</td><td>" . e($tx['account']) . "</td><td>" . e($tx['category']) . "<small class='d-block text-muted'>" . e($tx['description']) . "</small></td><td><span class='badge {$badge}'>" . e($tx['type']) . "</span></td><td class='{$txtCls} fw-bold'>{$sign} " . rp($tx['amount']) . "</td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
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
                    <div class="fs-4 fw-bold text-primary">{$bal1}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-3 border-start border-4 border-secondary bg-white">
                    <span class="badge bg-secondary align-self-start mb-2">REKENING PRIBADI</span>
                    <h5 class="fw-bold mb-1">Tabungan Rumah Tangga Bu Maryam</h5>
                    <div class="fs-4 fw-bold text-secondary">{$bal2}</div>
                </div>
            </div>
        </div>

        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3">Catat Pemasukan / Pengeluaran Kas</h6>
            <form action="/admin/financial" method="POST" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Rekening</label>
                    <select name="financial_account_id" class="form-select form-select-sm">
                        <option value="1">Kas Operasional Usaha (BUSINESS)</option>
                        <option value="2">Tabungan Rumah Tangga (PERSONAL)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Jenis</label>
                    <select name="transaction_type" class="form-select form-select-sm">
                        <option value="INCOME">INCOME (+)</option>
                        <option value="EXPENSE">EXPENSE (-)</option>
                    </select>
                </div>
                <div class="col-md-2"><label class="form-label small fw-bold">Nominal (Rp)</label><input type="number" name="amount" class="form-control form-control-sm" min="1" value="50000" required></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Keterangan</label><input type="text" name="description" class="form-control form-control-sm" placeholder="Contoh: Beli kemasan baru" required></div>
                <div class="col-md-2"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">Simpan Jurnal</button></div>
            </form>
        </div>

        <div class="card card-custom p-4 bg-white">
            <h6 class="fw-bold mb-3">Jurnal Mutasi Kas Harian:</h6>
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead><tr><th>Tanggal</th><th>Rekening</th><th>Kategori &amp; Keterangan</th><th>Jenis</th><th>Nominal</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminProfitLoss() {
    renderLayoutHeader("Laporan Laba Rugi");
    $txs = $_SESSION['preview']['transactions'];
    $businessIncome = 0;
    $businessExpense = 0;
    foreach ($txs as $t) {
        if ((int) ($t['account_id'] ?? 1) !== 1) {
            continue;
        }
        if ($t['type'] === 'INCOME') {
            $businessIncome += $t['amount'];
        } else {
            $businessExpense += $t['amount'];
        }
    }
    $net = $businessIncome - $businessExpense;
    $incRp = rp($businessIncome);
    $expRp = rp($businessExpense);
    $netPrefix = $net >= 0 ? '+ ' : '';
    $netRp = $netPrefix . rp($net);
    $netColor = $net >= 0 ? 'text-primary' : 'text-danger';

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark">Laporan Laba Rugi Sederhana (Akun Kas Usaha)</h3>
            <div>
                <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.print()">Cetak PDF</button>
                <a href="/admin/financial" class="btn btn-light btn-sm rounded-pill">Buku Kas</a>
            </div>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="row g-4 mb-2">
                <div class="col-md-4"><div class="p-3 bg-success bg-opacity-10 rounded"><h6>Total Pemasukan:</h6><strong class="fs-4 text-success">{$incRp}</strong></div></div>
                <div class="col-md-4"><div class="p-3 bg-danger bg-opacity-10 rounded"><h6>Total Pengeluaran:</h6><strong class="fs-4 text-danger">{$expRp}</strong></div></div>
                <div class="col-md-4"><div class="p-3 bg-primary bg-opacity-10 rounded"><h6>Laba Bersih:</h6><strong class="fs-4 {$netColor}">{$netRp}</strong></div></div>
            </div>
            <p class="small text-muted mb-0 mt-2"><i class="bi bi-shield-check"></i> Kalkulasi hanya menghitung akun operasional <code>BUSINESS</code> agar tidak tercampur rekening rumah tangga.</p>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminOrders() {
    renderLayoutHeader("Pesanan Masuk");
    $rows = '';
    foreach ($_SESSION['preview']['orders'] as $o) {
        $badge = in_array($o['status'], ['COMPLETED', 'PAID'], true) ? 'bg-success' : ($o['status'] === 'CANCELLED' ? 'bg-danger' : 'bg-warning text-dark');
        $opts = '';
        foreach (['PENDING', 'PAID', 'PROCESSING', 'READY_FOR_PICKUP', 'COMPLETED', 'CANCELLED'] as $st) {
            $sel = $o['status'] === $st ? 'selected' : '';
            $opts .= "<option value='{$st}' {$sel}>{$st}</option>";
        }
        $rows .= "<tr><td><strong>" . e($o['invoice']) . "</strong><small class='d-block text-muted'>" . e($o['items_text']) . "</small></td><td>" . e($o['time']) . "</td><td>" . e($o['customer']) . "</td><td class='fw-bold'>" . rp($o['total']) . "</td><td><span class='badge {$badge}'>" . e($o['status']) . "</span></td><td><form action='/admin/orders' method='POST' class='d-flex gap-1'><input type='hidden' name='order_id' value='" . (int) $o['id'] . "'><select name='status' class='form-select form-select-sm' style='width:140px;'>{$opts}</select><button type='submit' class='btn btn-outline-primary btn-sm'>Ubah</button></form></td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Daftar Transaksi Pesanan Masuk</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Invoice &amp; Item</th><th>Waktu</th><th>Pemesan</th><th>Total</th><th>Status FSM</th><th>Ubah Status</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminReservations() {
    renderLayoutHeader("Reservasi Wisata");
    $rows = '';
    foreach ($_SESSION['preview']['reservations'] as $r) {
        $badge = $r['status'] === 'ATTENDED' ? 'bg-success' : 'bg-primary';
        $rows .= "<tr><td><strong>" . e($r['code']) . "</strong></td><td>" . e($r['customer']) . "</td><td>" . e($r['date']) . "</td><td>" . (int) $r['visitors'] . " Orang</td><td>" . rp($r['total']) . "</td><td><span class='badge {$badge}'>" . e($r['status']) . "</span></td><td>" . e($r['check_in']) . "</td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Reservasi &amp; Validasi Kedatangan</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-2"><i class="bi bi-qr-code-scan me-1 text-primary"></i> Loket Validasi Check-In Wisatawan</h6>
            <form action="/admin/reservations" method="POST" class="row g-2 align-items-end">
                <div class="col-md-8"><label class="form-label small fw-bold">Masukkan Kode Booking</label><input type="text" name="booking_code" class="form-control form-control-sm" placeholder="Contoh: RSV-JMI-20261102-001" required></div>
                <div class="col-md-4"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">Validasi Check-In</button></div>
            </form>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Kode Booking</th><th>Pemesan</th><th>Tgl Kunjungan</th><th>Orang</th><th>Total</th><th>Status</th><th>Waktu Check-In</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminUmkm() {
    renderLayoutHeader("Mitra UMKM");
    $rows = '';
    foreach ($_SESSION['preview']['merchants'] as $m) {
        $isVer = $m['verification_status'] === 'VERIFIED';
        $badge = $isVer ? 'bg-success' : 'bg-danger';
        $nextStatus = $isVer ? 'SUSPENDED' : 'VERIFIED';
        $btnLabel = $isVer ? 'Tangguhkan' : 'Setujui';
        $btnCls = $isVer ? 'btn-outline-danger' : 'btn-success';
        $rows .= "<tr><td><strong>" . e($m['business_name']) . "</strong><small class='d-block text-muted'>" . e($m['address']) . "</small></td><td>" . e($m['owner_name']) . "</td><td>" . e($m['category']) . "</td><td><span class='badge {$badge}'>" . e($m['verification_status']) . "</span></td><td><form action='/admin/umkm' method='POST'><input type='hidden' name='merchant_id' value='" . (int) $m['id'] . "'><input type='hidden' name='status' value='{$nextStatus}'><button type='submit' class='btn {$btnCls} btn-sm rounded-pill px-3'>{$btnLabel}</button></form></td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Verifikasi Mitra Pelaku UMKM</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Nama Toko</th><th>Pemilik</th><th>Kategori</th><th>Status</th><th>Aksi Verifikasi</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminPartnerships() {
    renderLayoutHeader("Kemitraan");
    $rows = '';
    foreach ($_SESSION['preview']['partnerships'] as $idx => $p) {
        $num = $idx + 1;
        $rows .= "<tr><td>{$num}</td><td><strong>" . e($p['partner']) . "</strong></td><td><span class='badge bg-light text-dark border'>" . e($p['type']) . "</span></td><td>" . e($p['contact']) . "</td><td>" . e($p['period']) . "</td><td><span class='badge bg-success'>" . e($p['status']) . "</span></td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Arsip Kemitraan &amp; MoU</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3">Catat Kerja Sama Institusi Baru</h6>
            <form action="/admin/partnerships" method="POST" class="row g-2 align-items-end">
                <div class="col-md-4"><label class="form-label small fw-bold">Nama Instansi / Mitra</label><input type="text" name="partner_name" class="form-control form-control-sm" placeholder="Bank Jatim Cabang Pamekasan" required></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Jenis</label><select name="partnership_type" class="form-select form-select-sm"><option>PEMERINTAH</option><option>AKADEMISI</option><option>KOMUNITAS</option><option>SPONSOR</option></select></div>
                <div class="col-md-4"><label class="form-label small fw-bold">Kontak PIC</label><input type="text" name="contact_person" class="form-control form-control-sm" placeholder="Ibu Ratna (0812xxx)" required></div>
                <div class="col-md-2"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">+ Simpan MoU</button></div>
            </form>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead><tr><th>#</th><th>Instansi Mitra</th><th>Kategori</th><th>Kontak PIC</th><th>Periode</th><th>Status</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminEis() {
    renderLayoutHeader("EIS Analytics");
    $orders = $_SESSION['preview']['orders'];
    $merchants = $_SESSION['preview']['merchants'];
    $reservations = $_SESSION['preview']['reservations'];
    $totalRev = 3000000 + array_sum(array_column($orders, 'total'));
    $totalRevRp = rp($totalRev);
    $umkmCount = count($merchants);
    $orderCount = count($orders);
    $visitorCount = array_sum(array_column($reservations, 'visitors'));

    $merchantRows = '';
    foreach ($merchants as $m) {
        $prodCount = count(array_filter($_SESSION['preview']['products'], fn ($p) => (int) $p['umkm_merchant_id'] === (int) $m['id']));
        $merchantRows .= "<tr><td><strong class='text-primary'>" . e($m['business_name']) . "</strong></td><td>" . e($m['owner_name']) . "</td><td><span class='badge bg-light text-dark border'>" . e($m['category']) . "</span></td><td>{$prodCount} Produk</td><td class='fw-bold text-success'>" . rp($m['revenue']) . "</td></tr>";
    }

    echo <<<HTML
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark brand-font mb-0">Executive Information System (EIS) Lengkap</h3>
                <p class="text-muted small mb-0">Intelijen Bisnis &amp; Pengambilan Keputusan Kawasan Wisata Pantai Jumiang</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-primary px-3 py-2">MODUL EIS MOD-12</span>
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Perputaran Omzet</small><h4 class="fw-bold text-primary mb-0">{$totalRevRp}</h4></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">UMKM Terdaftar</small><h4 class="fw-bold text-success mb-0">{$umkmCount} Merchant</h4></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Total Transaksi</small><h4 class="fw-bold text-info mb-0">{$orderCount} Order</h4></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card card-custom p-4 bg-white"><small class="text-muted">Wisatawan Terdata</small><h4 class="fw-bold text-warning mb-0">{$visitorCount} Orang</h4></div></div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card card-custom p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Tren Penjualan Produk UMKM Kawasan</h5>
                    <div style="height: 290px;"><canvas id="eisTrendChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart text-info me-2"></i>Proporsi Sektor UMKM</h5>
                    <div style="height: 290px;"><canvas id="eisSectorChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="card card-custom p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-trophy text-warning me-2"></i>Kinerja Omzet Tiap Gerai UMKM</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Gerai UMKM</th><th>Pemilik</th><th>Kategori</th><th>Jumlah SKU</th><th>Total Omzet Transaksi</th></tr></thead>
                    <tbody>{$merchantRows}</tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById('eisTrendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'],
                    datasets: [{
                        label: 'Omzet Kawasan (IDR)',
                        data: [1200000, 1850000, 2400000, 2100000, 3100000, {$totalRev}],
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
            new Chart(document.getElementById('eisSectorChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Kuliner Laut (Produk)', 'Batik Pesisir', 'Homestay'],
                    datasets: [{
                        data: [3, 1, 1],
                        backgroundColor: ['#0284c7', '#16a34a', '#f59e0b']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
HTML;
    renderLayoutFooter();
}

function renderAdminReports() {
    renderLayoutHeader("Laporan");
    $orderCount = count($_SESSION['preview']['orders']);
    $resCount = count($_SESSION['preview']['reservations']);
    $txCount = count($_SESSION['preview']['transactions']);
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Pusat Rekapitulasi Laporan</h3>
            <div>
                <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak PDF</button>
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="card card-custom p-3 bg-white"><small class="text-muted">Transaksi Pesanan</small><h4 class="fw-bold text-primary mb-0">{$orderCount} Invoice</h4></div></div>
            <div class="col-md-4"><div class="card card-custom p-3 bg-white"><small class="text-muted">Mutasi Buku Kas</small><h4 class="fw-bold text-success mb-0">{$txCount} Jurnal</h4></div></div>
            <div class="col-md-4"><div class="card card-custom p-3 bg-white"><small class="text-muted">Reservasi Tiket</small><h4 class="fw-bold text-info mb-0">{$resCount} Booking</h4></div></div>
        </div>
        <div class="card card-custom p-4 bg-white">
            <p class="mb-2">Laporan transaksi, arus kas UMKM terpisah, dan reservasi tersedia dalam format siap cetak dan ekspor data.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="/admin/financial/profit-loss" class="btn btn-sm btn-success rounded-pill">Buka Laporan Laba Rugi</a>
                <a href="/admin/etl?download=csv" class="btn btn-sm btn-outline-primary rounded-pill">Ekspor Sampel CSV</a>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminAudit() {
    renderLayoutHeader("Audit Trail");
    $rows = '';
    foreach ($_SESSION['preview']['audit_logs'] as $log) {
        $rows .= "<tr><td>" . e($log['time']) . "</td><td><span class='badge bg-light text-dark border'>" . e($log['action']) . "</span></td><td>" . e($log['user']) . "</td><td>" . e($log['detail'] ?? '-') . "</td></tr>";
    }

    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-dark mb-0">Log Audit Trail Aktivitas Pengguna</h3>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill">Dashboard</a>
        </div>
        <div class="card card-custom p-4 bg-white">
            <div class="table-responsive">
                <table class="table small mb-0">
                    <thead><tr><th>Waktu</th><th>Aksi</th><th>User</th><th>Detail Mutasi</th></tr></thead>
                    <tbody>{$rows}</tbody>
                </table>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderForbidden(string $activeRole, array $allowedRoles) {
    http_response_code(403);
    $safeRole = e($activeRole);
    $safeAllowed = e(implode(', ', $allowedRoles));
    renderLayoutHeader("403 Akses Ditolak");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-5 mx-auto text-center" style="max-width: 560px;">
            <span class="badge bg-danger align-self-center mb-3 px-3 py-2">HTTP 403 FORBIDDEN</span>
            <h3 class="fw-bold text-dark mb-2">Akses Ditolak (RBAC Guard)</h3>
            <p class="text-muted mb-3">Role aktif Anda (<strong>{$safeRole}</strong>) tidak memiliki izin untuk mengakses modul ini. Role yang diizinkan: <code>{$safeAllowed}</code>.</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="/dashboard" class="btn btn-ocean rounded-pill px-4">Kembali ke Dashboard</a>
                <a href="/login" class="btn btn-outline-secondary rounded-pill px-4">Ganti Role Demo</a>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderNotFound(string $badUri) {
    http_response_code(404);
    $safeUri = e($badUri);
    renderLayoutHeader("404 Halaman Tidak Ditemukan");
    echo <<<HTML
    <div class="container py-5">
        <div class="card card-custom p-5 mx-auto text-center" style="max-width: 560px;">
            <span class="badge bg-secondary align-self-center mb-3 px-3 py-2">HTTP 404 NOT FOUND</span>
            <h3 class="fw-bold text-dark mb-2">Halaman Tidak Ditemukan</h3>
            <p class="text-muted mb-4">Rute <code>{$safeUri}</code> tidak tersedia pada sistem Jumiang Smart Tourism &amp; UMKM.</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="/" class="btn btn-ocean rounded-pill px-4">Kembali ke Beranda</a>
                <a href="/katalog-umkm" class="btn btn-outline-primary rounded-pill px-4">Lihat Marketplace</a>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function renderAdminEtl() {
    require_once __DIR__ . '/../app/Services/EtlMigrationService.php';
    $sampleCsv = __DIR__ . '/../database/samples/raw_umkm_survey.csv';

    if (isset($_GET['download']) && $_GET['download'] === 'csv' && is_file($sampleCsv)) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="raw_umkm_survey.csv"');
        readfile($sampleCsv);
        exit;
    }

    if (!empty($_SESSION['preview']['custom_etl_report'])) {
        $report = $_SESSION['preview']['custom_etl_report'];
    } else {
        $etl = new \App\Services\EtlMigrationService();
        $report = $etl->processUmkmSurveyCsv($sampleCsv, null, true);
    }

    renderLayoutHeader("ETL Data Migration & CSV");
    $zeroBadge = $report['zero_data_loss']
        ? '<span class="badge bg-success">PASS (Zero Data Loss)</span>'
        : '<span class="badge bg-danger">FAIL</span>';

    $validRowsHtml = '';
    foreach ($report['valid_records'] as $rec) {
        $validRowsHtml .= "<tr><td>" . e($rec['business_name']) . "</td><td>" . e($rec['owner_name']) . "</td><td><span class='badge bg-info text-dark'>" . e($rec['category']) . "</span></td><td><code>" . e($rec['phone_number']) . "</code></td><td>" . e($rec['latitude'] . ', ' . $rec['longitude']) . "</td></tr>";
    }
    if ($validRowsHtml === '') {
        $validRowsHtml = "<tr><td colspan='5' class='text-center text-muted'>Tidak ada baris valid.</td></tr>";
    }

    $quarantineRowsHtml = '';
    foreach ($report['quarantine_details'] as $q) {
        $quarantineRowsHtml .= "<tr><td>Baris " . (int) $q['row_number'] . "</td><td class='text-danger'>" . e($q['reason']) . "</td></tr>";
    }
    if ($quarantineRowsHtml === '') {
        $quarantineRowsHtml = "<tr><td colspan='2' class='text-center text-muted'>0 baris dikarantina.</td></tr>";
    }

    $srcFile = e($report['source_file']);
    echo <<<HTML
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-0">Modul Import / Export CSV &amp; ETL Lapangan</h3>
                <small class="text-muted">Extract - Transform - Load &amp; Quarantine Reconciliation (Minggu 13)</small>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/etl?download=csv" class="btn btn-success btn-sm rounded-pill px-3"><i class="bi bi-download me-1"></i> Export Template CSV</a>
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Dashboard</a>
            </div>
        </div>

        <div class="card card-custom p-4 bg-white mb-4">
            <h6 class="fw-bold mb-2">Uji Impor File CSV Survei UMKM (Dry-Run Validation)</h6>
            <form action="/admin/etl" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                <div class="col-md-7"><input type="file" name="csv_file" accept=".csv,text/csv" class="form-control form-control-sm"></div>
                <div class="col-md-3"><button type="submit" class="btn btn-ocean btn-sm w-100 rounded-pill fw-bold">Jalankan Dry-Run ETL</button></div>
                <div class="col-md-2"><button type="submit" name="reset_etl" value="1" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">Reset Sampel</button></div>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6"><div class="card card-custom p-3 bg-white"><small class="text-muted">Diekstrak</small><h4 class="fw-bold text-primary mb-0">{$report['extracted_rows']} Baris</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-3 bg-white"><small class="text-muted">Valid &amp; Bersih</small><h4 class="fw-bold text-success mb-0">{$report['valid_transformed_rows']} Baris</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-3 bg-white"><small class="text-muted">Duplikat Dilewati</small><h4 class="fw-bold text-warning mb-0">{$report['duplicates_skipped']} Baris</h4></div></div>
            <div class="col-md-3 col-6"><div class="card card-custom p-3 bg-white"><small class="text-muted">Karantina Anomali</small><h4 class="fw-bold text-danger mb-0">{$report['quarantined_rows']} Baris</h4></div></div>
        </div>
        <div class="card card-custom p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Rekonsiliasi Sumber ({$srcFile})</h6>
                {$zeroBadge}
            </div>
            <p class="small text-muted mb-3">Normalisasi nomor telepon ke <code>+62</code>, pembersihan format Rupiah, validasi koordinat WGS84 Pamekasan (-7.5 s/d -7.0, 113.3 s/d 113.8), serta karantina otomatis baris tidak valid.</p>
            <div class="row g-3">
                <div class="col-lg-8">
                    <h6 class="small fw-bold text-success">Data Valid Siap Muat</h6>
                    <div class="table-responsive"><table class="table table-sm small mb-0"><thead><tr><th>Toko</th><th>Pemilik</th><th>Kategori</th><th>WA (+62)</th><th>Koordinat</th></tr></thead><tbody>{$validRowsHtml}</tbody></table></div>
                </div>
                <div class="col-lg-4">
                    <h6 class="small fw-bold text-danger">Log Karantina Anomali</h6>
                    <div class="table-responsive"><table class="table table-sm small mb-0"><thead><tr><th>Baris</th><th>Alasan Karantina</th></tr></thead><tbody>{$quarantineRowsHtml}</tbody></table></div>
                </div>
            </div>
        </div>
    </div>
HTML;
    renderLayoutFooter();
}

function handleApiRequest(string $uri, string $method) {
    header('Content-Type: application/json; charset=utf-8');

    $markers = [
        ['id' => 1, 'category' => 'WISATA', 'title' => 'Puncak Tebing Karang Jumiang', 'description' => 'Spot pemandangan laut dari atas tebing karang tinggi.', 'latitude' => -7.228912, 'longitude' => 113.538120, 'icon_type' => 'camera'],
        ['id' => 2, 'category' => 'WISATA', 'title' => 'Sunset Point Pantai Jumiang', 'description' => 'Titik menikmati matahari terbenam terbaik di tebing selatan.', 'latitude' => -7.229450, 'longitude' => 113.537540, 'icon_type' => 'sun'],
        ['id' => 3, 'category' => 'UMKM', 'title' => 'Gerai Sentra Lorjuk Bu Maryam', 'description' => 'Pusat oleh-oleh rengginang lorjuk, keripik ikan, dan terasi khas Jumiang.', 'latitude' => -7.227800, 'longitude' => 113.536900, 'icon_type' => 'shop'],
        ['id' => 4, 'category' => 'UMKM', 'title' => 'Galeri Batik Pesisir Tanjung', 'description' => 'Kain batik tulis corak bahari khas nelayan Desa Tanjung.', 'latitude' => -7.227200, 'longitude' => 113.536100, 'icon_type' => 'palette'],
        ['id' => 5, 'category' => 'HOMESTAY', 'title' => 'Homestay Bahari Bu Sugiarti', 'description' => 'Penginapan tepi pantai berfasilitas lengkap.', 'latitude' => -7.226500, 'longitude' => 113.535800, 'icon_type' => 'home'],
        ['id' => 6, 'category' => 'FASILITAS', 'title' => 'Musholla Nurul Bahari & Toilet Umum', 'description' => 'Fasilitas ibadah dan kamar bilas air tawar.', 'latitude' => -7.228100, 'longitude' => 113.537300, 'icon_type' => 'life-preserver'],
        ['id' => 7, 'category' => 'PARKIR', 'title' => 'Area Parkir Utama Wisata Pantai Jumiang', 'description' => 'Kapasitas muat 50 mobil dan 200 sepeda motor.', 'latitude' => -7.226900, 'longitude' => 113.536500, 'icon_type' => 'truck'],
    ];

    $products = [
        1 => ['id' => 1, 'umkm_merchant_id' => 1, 'category_id' => 1, 'name' => 'Rengginang Lorjuk Super Khas Jumiang', 'slug' => 'rengginang-lorjuk-super', 'price' => 35000, 'stock' => 45, 'unit' => 'bungkus'],
        2 => ['id' => 2, 'umkm_merchant_id' => 1, 'category_id' => 1, 'name' => 'Keripik Ikan Teri Krispi Gurih', 'slug' => 'keripik-ikan-teri-krispi', 'price' => 20000, 'stock' => 30, 'unit' => 'bungkus'],
        3 => ['id' => 3, 'umkm_merchant_id' => 1, 'category_id' => 1, 'name' => 'Terasi Asli Tanjung Madura (Super)', 'slug' => 'terasi-asli-tanjung', 'price' => 15000, 'stock' => 60, 'unit' => 'pcs'],
        4 => ['id' => 4, 'umkm_merchant_id' => 2, 'category_id' => 2, 'name' => 'Kain Batik Tulis Motif Bahari Pamekasan', 'slug' => 'kain-batik-tulis-motif-bahari', 'price' => 175000, 'stock' => 8, 'unit' => 'lembar'],
        5 => ['id' => 5, 'umkm_merchant_id' => 3, 'category_id' => 4, 'name' => 'Sewa Kamar Homestay Standard View Laut', 'slug' => 'sewa-kamar-homestay', 'price' => 250000, 'stock' => 4, 'unit' => 'malam'],
    ];

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $bearerRole = null;
    if (preg_match('/^Bearer\s+demo-token-(.+)$/i', trim($authHeader), $m)) {
        $bearerRole = $m[1];
    }
    $apiRole = $bearerRole ?? ($_SESSION['role'] ?? null);

    if ($uri === '/api/v1/destinations' && $method === 'GET') {
        echo json_encode([
            'status' => 'success',
            'data' => [[
                'id' => 1,
                'name' => 'Pantai Jumiang Bahari',
                'slug' => 'pantai-jumiang-bahari',
                'village' => 'Tanjung',
                'district' => 'Pademawu',
                'regency' => 'Pamekasan',
                'operating_hours' => '06:00 - 18:00 WIB',
                'ticket_price_demo' => 10000,
                'is_demo_data' => true,
            ]],
        ]);
        return;
    }

    if ($uri === '/api/v1/map-markers' && $method === 'GET') {
        $cat = strtoupper((string) ($_GET['category'] ?? 'ALL'));
        $filtered = ($cat === '' || $cat === 'ALL')
            ? $markers
            : array_values(array_filter($markers, fn ($m) => $m['category'] === $cat));

        echo json_encode([
            'status' => 'success',
            'data' => $filtered,
            'meta' => [
                'center' => [-7.228912, 113.538120],
                'default_zoom' => 15,
                'coordinate_system' => 'WGS84',
            ],
        ]);
        return;
    }

    if ($uri === '/api/v1/products' && $method === 'GET') {
        $catId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;
        $list = array_values($products);
        if ($catId) {
            $list = array_values(array_filter($list, fn ($p) => $p['category_id'] === $catId));
        }
        echo json_encode(['status' => 'success', 'data' => $list]);
        return;
    }

    if (preg_match('#^/api/v1/products/(\d+)$#', $uri, $m) && $method === 'GET') {
        $id = (int) $m[1];
        if (!isset($products[$id])) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
            return;
        }
        echo json_encode(['status' => 'success', 'data' => $products[$id]]);
        return;
    }

    if ($uri === '/api/v1/orders/simulate' && $method === 'POST') {
        if ($apiRole === null) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated. Sertakan Bearer token atau sesi login.']);
            return;
        }
        $payload = json_decode((string) file_get_contents('php://input'), true) ?: [];
        $items = $payload['items'] ?? [];
        if (empty($items) || !is_array($items)) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Field items wajib diisi.']);
            return;
        }
        $total = 0;
        foreach ($items as $item) {
            $pid = (int) ($item['product_id'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            if (!isset($products[$pid]) || $qty < 1 || $qty > $products[$pid]['stock']) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Produk tidak valid atau stok tidak mencukupi.']);
                return;
            }
            $total += $products[$pid]['price'] * $qty;
        }
        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'order_id' => 101,
                'invoice_no' => 'INV-JMI-' . date('Ymd') . '-SIM',
                'total_amount' => $total,
                'order_status' => 'PENDING',
                'payment_method' => $payload['payment_method'] ?? 'SIMULATION_QRIS',
                'payment_status' => 'UNPAID',
                'is_demo_gateway' => true,
            ],
        ]);
        return;
    }

    if ($uri === '/api/v1/payments/simulate-pay' && $method === 'POST') {
        if ($apiRole === null) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated.']);
            return;
        }
        $payload = json_decode((string) file_get_contents('php://input'), true) ?: [];
        if (empty($payload['order_id'])) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'order_id wajib diisi.']);
            return;
        }
        echo json_encode([
            'status' => 'success',
            'data' => [
                'order_id' => (int) $payload['order_id'],
                'order_status' => 'PAID',
                'payment_status' => 'PAID',
                'inventory_deducted' => true,
                'financial_ledger_recorded' => true,
                'account_type' => 'BUSINESS',
            ],
        ]);
        return;
    }

    if ($uri === '/api/v1/umkm/my-finances' && $method === 'GET') {
        if ($apiRole === null) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated.']);
            return;
        }
        if (!in_array($apiRole, ['pelaku_umkm', 'super_admin'], true)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Forbidden: Akses buku kas khusus Pelaku UMKM.']);
            return;
        }
        echo json_encode([
            'status' => 'success',
            'data' => [
                'umkm_merchant_id' => 1,
                'tenant_isolated' => true,
                'accounts' => [
                    ['account_name' => 'Kas Operasional Usaha Lorjuk', 'account_type' => 'BUSINESS', 'current_balance' => 1525000.00],
                    ['account_name' => 'Tabungan Rumah Tangga Pribadi Bu Maryam', 'account_type' => 'PERSONAL', 'current_balance' => 500000.00],
                ],
                'profit_loss' => [
                    'total_income' => 925000.00,
                    'total_expense' => 400000.00,
                    'net_profit' => 525000.00,
                ],
            ],
        ]);
        return;
    }

    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'API endpoint not found.']);
}
