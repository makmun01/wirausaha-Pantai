<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Assert;
use Tests\HttpClient;

class ApiAndIntegrationTest extends TestCase
{
    public function test_public_api_v1_endpoints_return_expected_contracts(): void
    {
        $client = new HttpClient();

        // 1. Health endpoint
        $health = $client->get('/health');
        Assert::same(200, $health['status']);
        Assert::same('ok', $health['json']['status'] ?? null);

        // 2. Destinations
        $dest = $client->get('/api/v1/destinations');
        Assert::same(200, $dest['status']);
        Assert::same('Tanjung', $dest['json']['data'][0]['village'] ?? null);
        Assert::true((bool) ($dest['json']['data'][0]['is_demo_data'] ?? false));

        // 3. GIS Map Markers + Category Filter
        $allMarkers = $client->get('/api/v1/map-markers');
        Assert::same(200, $allMarkers['status']);
        Assert::same(7, count($allMarkers['json']['data'] ?? []));
        Assert::same('WGS84', $allMarkers['json']['meta']['coordinate_system'] ?? null);

        $umkmMarkers = $client->get('/api/v1/map-markers?category=UMKM');
        Assert::same(200, $umkmMarkers['status']);
        Assert::same(2, count($umkmMarkers['json']['data'] ?? []));
        foreach ($umkmMarkers['json']['data'] as $marker) {
            Assert::same('UMKM', $marker['category']);
        }

        // 4. Products catalog & detail + 404 handling
        $products = $client->get('/api/v1/products');
        Assert::same(200, $products['status']);
        Assert::same(5, count($products['json']['data'] ?? []));

        $product1 = $client->get('/api/v1/products/1');
        Assert::same(200, $product1['status']);
        Assert::same('rengginang-lorjuk-super', $product1['json']['data']['slug'] ?? null);

        $product404 = $client->get('/api/v1/products/999');
        Assert::same(404, $product404['status']);
    }

    public function test_protected_order_payment_and_umkm_finance_apis(): void
    {
        $client = new HttpClient();

        // Unauthenticated requests must be rejected with 401
        Assert::same(401, $client->postJson('/api/v1/orders/simulate', ['items' => [['product_id' => 1, 'quantity' => 1]]])['status']);
        Assert::same(401, $client->get('/api/v1/umkm/my-finances')['status']);

        // Simulate order & payment with Bearer token
        $wisatawanHeaders = ['Authorization: Bearer demo-token-wisatawan'];
        $orderResp = $client->postJson('/api/v1/orders/simulate', [
            'umkm_merchant_id' => 1,
            'items' => [['product_id' => 1, 'quantity' => 2]],
            'payment_method' => 'SIMULATION_QRIS',
        ], $wisatawanHeaders);
        Assert::same(201, $orderResp['status']);
        Assert::same(70000, $orderResp['json']['data']['total_amount'] ?? null);
        Assert::same('PENDING', $orderResp['json']['data']['order_status'] ?? null);

        $payResp = $client->postJson('/api/v1/payments/simulate-pay', [
            'order_id' => $orderResp['json']['data']['order_id'],
        ], $wisatawanHeaders);
        Assert::same(200, $payResp['status']);
        Assert::same('PAID', $payResp['json']['data']['order_status'] ?? null);
        Assert::true((bool) ($payResp['json']['data']['inventory_deducted'] ?? false));
        Assert::true((bool) ($payResp['json']['data']['financial_ledger_recorded'] ?? false));

        // Tenant-isolated UMKM finances: Wisatawan rejected 403, Pelaku UMKM allowed 200
        Assert::same(403, $client->get('/api/v1/umkm/my-finances', $wisatawanHeaders)['status']);

        $umkmFin = $client->get('/api/v1/umkm/my-finances', ['Authorization: Bearer demo-token-pelaku_umkm']);
        Assert::same(200, $umkmFin['status']);
        Assert::true((bool) ($umkmFin['json']['data']['tenant_isolated'] ?? false));
        Assert::same(2, count($umkmFin['json']['data']['accounts'] ?? []));
        Assert::same('BUSINESS', $umkmFin['json']['data']['accounts'][0]['account_type'] ?? null);
        Assert::same('PERSONAL', $umkmFin['json']['data']['accounts'][1]['account_type'] ?? null);
    }
}
