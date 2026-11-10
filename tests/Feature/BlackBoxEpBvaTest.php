<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Assert;
use Tests\HttpClient;

class BlackBoxEpBvaTest extends TestCase
{
    public function test_public_portal_pages_and_demo_label_compliance(): void
    {
        $client = new HttpClient();
        $routes = [
            '/' => 'Pantai Jumiang',
            '/tentang-jumiang' => 'Puncak Tebing Jumiang',
            '/peta-wisata' => 'WGS84 Datum',
            '/katalog-umkm' => 'Etalase Digital Produk UMKM Jumiang',
            '/produk/rengginang-lorjuk-super' => 'Rengginang Lorjuk Super Khas Jumiang',
            '/keranjang' => 'Keranjang Belanja Anda',
            '/reservasi-wisata' => 'Reservasi Tiket Pantai Jumiang',
            '/login' => 'AKUN DEMO CEPAT',
            '/register' => 'Daftar Akun Wisatawan',
        ];

        foreach ($routes as $path => $expectedText) {
            $resp = $client->get($path);
            Assert::same(200, $resp['status'], "Rute publik {$path} harus HTTP 200.");
            Assert::contains($expectedText, $resp['body']);
            Assert::contains('[DATA DEMO / SIMULASI]', $resp['body']);
        }
    }

    public function test_order_quantity_equivalence_partitioning_and_boundary_values(): void
    {
        $client = new HttpClient();
        $headers = ['Authorization: Bearer demo-token-wisatawan'];

        // Invalid EP: quantity = 0 (lower invalid boundary)
        $zeroQty = $client->postJson('/api/v1/orders/simulate', [
            'items' => [['product_id' => 4, 'quantity' => 0]],
        ], $headers);
        Assert::same(422, $zeroQty['status']);

        // Invalid EP: negative quantity
        $negQty = $client->postJson('/api/v1/orders/simulate', [
            'items' => [['product_id' => 4, 'quantity' => -1]],
        ], $headers);
        Assert::same(422, $negQty['status']);

        // Valid BVA: min valid quantity = 1
        $minValid = $client->postJson('/api/v1/orders/simulate', [
            'items' => [['product_id' => 4, 'quantity' => 1]],
        ], $headers);
        Assert::same(201, $minValid['status']);

        // Valid BVA: max available stock = 8 for Product 4 (Kain Batik Tulis)
        $maxValid = $client->postJson('/api/v1/orders/simulate', [
            'items' => [['product_id' => 4, 'quantity' => 8]],
        ], $headers);
        Assert::same(201, $maxValid['status']);

        // Invalid BVA: stock + 1 = 9 exceeds available stock
        $overStock = $client->postJson('/api/v1/orders/simulate', [
            'items' => [['product_id' => 4, 'quantity' => 9]],
        ], $headers);
        Assert::same(422, $overStock['status']);
        Assert::same('error', $overStock['json']['status'] ?? null);
    }
}
