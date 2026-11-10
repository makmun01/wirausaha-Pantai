<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Assert;
use Tests\HttpClient;

class AuthAndRbacTest extends TestCase
{
    public function test_demo_login_switches_session_for_all_six_roles(): void
    {
        $roles = [
            'super_admin' => 'Administrator Jumiang',
            'pengelola_wisata' => 'Pokdarwis Pantai Jumiang',
            'pelaku_umkm' => 'Ibu Maryam',
            'bendahara' => 'Bendahara Kawasan Jumiang',
            'pimpinan' => 'Kepala Desa Tanjung',
            'wisatawan' => 'Budi Wisatawan',
        ];

        foreach ($roles as $role => $expectedName) {
            $client = new HttpClient(null, "role_{$role}");
            $resp = $client->get("/demo-login?role={$role}");
            Assert::same(200, $resp['status'], "Role {$role} gagal masuk dashboard.");
            Assert::contains($expectedName, $resp['body']);
            Assert::contains("Role Aktif: {$role}", $resp['body']);
        }
    }

    public function test_wisatawan_gets_403_forbidden_on_admin_modules(): void
    {
        $client = new HttpClient(null, 'wisatawan_rbac');
        $client->get('/demo-login?role=wisatawan');

        $restrictedPaths = [
            '/admin/dashboard',
            '/admin/financial',
            '/admin/products',
            '/admin/umkm',
            '/admin/etl',
        ];

        foreach ($restrictedPaths as $path) {
            $resp = $client->get($path);
            Assert::same(403, $resp['status'], "Wisatawan seharusnya ditolak (403) pada {$path}");
            Assert::contains('Akses Ditolak', $resp['body']);
        }
    }

    public function test_pelaku_umkm_and_super_admin_role_boundaries(): void
    {
        $umkmClient = new HttpClient(null, 'umkm_rbac');
        $umkmClient->get('/demo-login?role=pelaku_umkm');

        Assert::same(200, $umkmClient->get('/admin/products')['status']);
        Assert::same(200, $umkmClient->get('/admin/financial')['status']);
        Assert::same(403, $umkmClient->get('/admin/umkm')['status']);
        Assert::same(403, $umkmClient->get('/admin/audit-logs')['status']);

        $adminClient = new HttpClient(null, 'super_admin_rbac');
        $adminClient->get('/demo-login?role=super_admin');

        foreach (['/admin/umkm', '/admin/partnerships', '/admin/audit-logs', '/admin/etl', '/admin/eis'] as $path) {
            Assert::same(200, $adminClient->get($path)['status'], "Super Admin wajib dapat mengakses {$path}");
        }

        $csvDownload = $adminClient->get('/admin/etl?download=csv');
        Assert::same(200, $csvDownload['status']);
        Assert::contains('Nama_Toko,Nama_Pemilik', $csvDownload['body']);
    }
}
