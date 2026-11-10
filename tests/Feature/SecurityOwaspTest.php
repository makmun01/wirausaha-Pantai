<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Assert;
use Tests\HttpClient;

class SecurityOwaspTest extends TestCase
{
    public function test_reflected_xss_payloads_are_escaped(): void
    {
        $client = new HttpClient();
        $payload = "<script>alert('xss')</script>";
        $encoded = urlencode($payload);

        $catalogResp = $client->get("/katalog-umkm?search={$encoded}");
        Assert::same(200, $catalogResp['status']);
        Assert::notContains($payload, $catalogResp['body'], 'Reflected XSS payload tidak boleh dirender mentah pada katalog.');
        Assert::contains('&lt;script&gt;', $catalogResp['body']);

        $productResp = $client->get('/produk/' . urlencode('<script>alert(1)</script>'));
        Assert::same(200, $productResp['status']);
        Assert::notContains('<script>alert(1)</script>', $productResp['body'], 'Slug produk wajib di-escape terhadap XSS.');
    }

    public function test_http_security_hardening_headers_and_gitignore_env_hygiene(): void
    {
        $client = new HttpClient();
        $home = $client->get('/');
        Assert::same(200, $home['status']);
        Assert::same('SAMEORIGIN', $home['headers']['x-frame-options'] ?? null);
        Assert::same('nosniff', $home['headers']['x-content-type-options'] ?? null);
        Assert::same('strict-origin-when-cross-origin', $home['headers']['referrer-policy'] ?? null);

        $gitignore = file_get_contents(__DIR__ . '/../../.gitignore');
        Assert::true($gitignore !== false);
        Assert::true((bool) preg_match('/^\.env\r?$/m', $gitignore), '.gitignore wajib mengabaikan file .env.');
    }
}
