<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Assert;
use Tests\HttpClient;

class InteractiveUiFlowsTest extends TestCase
{
    public function test_map_catalog_search_and_five_products_render_without_errors(): void
    {
        $client = new HttpClient(null, 'ui_public');

        // Mobile navbar toggler, inline SVG favicon, & 404 route handling
        $home = $client->get('/');
        Assert::same(200, $home['status']);
        Assert::contains('navbar-toggler', $home['body']);
        Assert::contains('data:image/svg+xml', $home['body']);
        Assert::same(404, $client->get('/halaman-tidak-ditemukan-xyz')['status']);

        // GIS Map filter UI
        $mapAll = $client->get('/peta-wisata');
        Assert::same(200, $mapAll['status']);
        Assert::contains('Menampilkan 7 Marker Aktif', $mapAll['body']);

        $mapUmkm = $client->get('/peta-wisata?category=UMKM');
        Assert::same(200, $mapUmkm['status']);
        Assert::contains('Menampilkan 2 Marker Aktif', $mapUmkm['body']);

        // Catalog search & category filter UI across 5 products
        $catalogAll = $client->get('/katalog-umkm');
        Assert::same(200, $catalogAll['status']);
        Assert::contains('Terasi Asli Tanjung Madura (Super)', $catalogAll['body']);
        Assert::contains('Sewa Kamar Homestay Standard View Laut', $catalogAll['body']);

        $catalogBatik = $client->get('/katalog-umkm?category=batik');
        Assert::same(200, $catalogBatik['status']);
        Assert::contains('Kain Batik Tulis Motif Bahari Pamekasan', $catalogBatik['body']);
        Assert::notContains('Terasi Asli Tanjung Madura (Super)', $catalogBatik['body']);

        Assert::same(200, $client->get('/produk/terasi-asli-tanjung')['status']);
        Assert::same(200, $client->get('/produk/sewa-kamar-homestay')['status']);
    }

    public function test_cart_checkout_and_reservation_interactive_flows(): void
    {
        $client = new HttpClient(null, 'ui_buyer');

        // Add product #3 (Terasi Rp 15.000) to cart & verify on /keranjang
        $cartResp = $client->postForm('/keranjang', [
            'action' => 'add',
            'product_id' => 3,
            'quantity' => 2,
        ]);
        Assert::same(200, $cartResp['status']);
        Assert::contains('Terasi Asli Tanjung Madura (Super)', $cartResp['body']);
        Assert::contains('Rp 120.000', $cartResp['body']);

        // Complete simulated checkout -> renders PAID invoice receipt
        $checkoutResp = $client->postForm('/checkout', [
            'payment_method' => 'SIMULATION_QRIS',
            'delivery_option' => 'PICKUP',
        ]);
        Assert::same(200, $checkoutResp['status']);
        Assert::contains('PEMBAYARAN SIMULASI LUNAS (PAID)', $checkoutResp['body']);
        Assert::contains('INV-JMI-', $checkoutResp['body']);

        // Reservation boundary fail (> 500) & valid booking -> check-in
        $badRes = $client->postForm('/reservasi-wisata', [
            'visitor_count' => 999,
            'visit_date' => '2026-11-20',
            'package_name' => 'Tiket Masuk Reguler',
        ]);
        Assert::same(200, $badRes['status']);
        Assert::contains('harus antara 1 sampai 500', $badRes['body']);

        $okRes = $client->postForm('/reservasi-wisata', [
            'visitor_count' => 3,
            'visit_date' => '2026-11-20',
            'package_name' => 'Paket Wisata Keluarga & Tebing',
        ]);
        Assert::same(200, $okRes['status']);
        Assert::contains('E-Tiket Reservasi Diterbitkan!', $okRes['body']);
        Assert::contains('Rp 30.000', $okRes['body']);
    }

    public function test_financial_pl_parity_fsm_guard_and_eis_charts(): void
    {
        $umkm = new HttpClient(null, 'ui_umkm');
        $umkm->get('/demo-login?role=pelaku_umkm');

        // Verify all 4 financial transactions + matching Profit-Loss totals (925.000 / 400.000 / 525.000)
        $fin = $umkm->get('/admin/financial');
        Assert::same(200, $fin['status']);
        Assert::contains('Biaya Kemasan', $fin['body']);

        $pl = $umkm->get('/admin/financial/profit-loss');
        Assert::same(200, $pl['status']);
        Assert::contains('Rp 925.000', $pl['body']);
        Assert::contains('Rp 400.000', $pl['body']);
        Assert::contains('+ Rp 525.000', $pl['body']);

        // Add INCOME Rp 100.000 to BUSINESS account & confirm balance increases cleanly
        $addTx = $umkm->postForm('/admin/financial', [
            'financial_account_id' => 1,
            'transaction_type' => 'INCOME',
            'amount' => 100000,
            'description' => 'Penjualan paket oleh-oleh sore',
        ]);
        Assert::same(200, $addTx['status']);
        Assert::contains('Rp 1.625.000', $addTx['body']);

        // Order FSM check on /admin/orders (order 2 is PAID -> COMPLETED invalid, PAID -> PROCESSING valid)
        $invalidFsm = $umkm->postForm('/admin/orders', ['order_id' => 2, 'status' => 'PENDING']);
        Assert::same(200, $invalidFsm['status']);
        Assert::contains('Transisi FSM ditolak', $invalidFsm['body']);

        $validFsm = $umkm->postForm('/admin/orders', ['order_id' => 2, 'status' => 'PROCESSING']);
        Assert::same(200, $validFsm['status']);
        Assert::contains('PAID -&gt; PROCESSING', $validFsm['body']);

        // Super Admin EIS charts & ETL form
        $admin = new HttpClient(null, 'ui_admin');
        $admin->get('/demo-login?role=super_admin');
        $eis = $admin->get('/admin/eis');
        Assert::same(200, $eis['status']);
        Assert::contains('eisTrendChart', $eis['body']);
        Assert::contains('eisSectorChart', $eis['body']);
        Assert::contains('Homestay Bahari Jumiang Asri', $eis['body']);
    }

    public function test_remaining_admin_forms_register_and_zero_php_warnings_across_routes(): void
    {
        // Register validation & success
        $regClient = new HttpClient(null, 'ui_register');
        $badReg = $regClient->postForm('/register', ['name' => 'Test', 'email' => 'not-an-email', 'password' => '123']);
        Assert::same(200, $badReg['status']);
        Assert::contains('minimal 6 karakter', $badReg['body']);

        $okReg = $regClient->postForm('/register', ['name' => 'Rina Wisatawan', 'email' => 'rina@test.com', 'password' => 'rahasia123']);
        Assert::same(200, $okReg['status']);
        Assert::contains('Pendaftaran akun demo untuk Rina Wisatawan berhasil', $okReg['body']);

        // Super Admin CRUD / FSM interactive actions
        $admin = new HttpClient(null, 'ui_super_forms');
        $admin->get('/demo-login?role=super_admin');

        $badProd = $admin->postForm('/admin/products', ['name' => 'Tes Negatif', 'price' => -1, 'stock' => 5]);
        Assert::contains('Input ditolak (BVA)', $badProd['body']);

        $okProd = $admin->postForm('/admin/products', ['name' => 'Petis Madura Gratis Sampel', 'price' => 0, 'stock' => 10]);
        Assert::contains('Petis Madura Gratis Sampel', $okProd['body']);

        $invAdj = $admin->postForm('/admin/inventory', ['product_id' => 1, 'type' => 'IN', 'quantity' => 5, 'notes' => 'Restok uji']);
        Assert::contains('Mutasi stok berhasil dicatat', $invAdj['body']);

        $checkIn = $admin->postForm('/admin/reservations', ['booking_code' => 'RSV-JMI-20261102-001']);
        Assert::contains('Check-in berhasil', $checkIn['body']);

        $suspendUmkm = $admin->postForm('/admin/umkm', ['merchant_id' => 3, 'status' => 'SUSPENDED']);
        Assert::contains('SUSPENDED', $suspendUmkm['body']);

        // Sweep all 23 web routes verifying HTTP 200 and zero PHP runtime warnings/errors
        $allWebRoutes = [
            '/', '/tentang-jumiang', '/peta-wisata', '/katalog-umkm',
            '/produk/rengginang-lorjuk-super', '/keranjang', '/checkout', '/reservasi-wisata',
            '/login', '/register', '/dashboard', '/admin/products', '/admin/inventory',
            '/admin/financial', '/admin/financial/profit-loss', '/admin/orders',
            '/admin/reservations', '/admin/umkm', '/admin/partnerships', '/admin/eis',
            '/admin/reports', '/admin/audit-logs', '/admin/etl',
        ];
        foreach ($allWebRoutes as $route) {
            $resp = $admin->get($route);
            Assert::same(200, $resp['status'], "Rute {$route} harus mengembalikan HTTP 200.");
            Assert::notContains('Fatal error', $resp['body'], "Fatal error ditemukan pada {$route}");
            Assert::notContains('Parse error', $resp['body'], "Parse error ditemukan pada {$route}");
            Assert::notContains('Warning:', $resp['body'], "PHP Warning ditemukan pada {$route}");
            Assert::notContains('Notice:', $resp['body'], "PHP Notice ditemukan pada {$route}");
        }
    }
}
