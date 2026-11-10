<?php

declare(strict_types=1);

$baseUrl = rtrim($argv[1] ?? 'http://127.0.0.1:18085', '/');
$tiers = [
    ['name' => 'Tier 1 - Baseline', 'concurrency' => 5, 'requests' => 20],
    ['name' => 'Tier 2 - Moderate', 'concurrency' => 10, 'requests' => 50],
    ['name' => 'Tier 3 - Peak Demo', 'concurrency' => 20, 'requests' => 100],
];
$endpoints = ['/', '/peta-wisata', '/katalog-umkm', '/api/v1/map-markers', '/api/v1/products'];

echo "=== JUMIANG LOCAL PERFORMANCE BENCHMARK ===" . PHP_EOL;
echo "Target: {$baseUrl}" . PHP_EOL . PHP_EOL;

foreach ($tiers as $tier) {
    $latenciesMs = [];
    $errors = 0;
    $totalStart = microtime(true);
    $sent = 0;

    while ($sent < $tier['requests']) {
        $batchSize = min($tier['concurrency'], $tier['requests'] - $sent);
        $mh = curl_multi_init();
        $handles = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $path = $endpoints[($sent + $i) % count($endpoints)];
            $ch = curl_init($baseUrl . $path);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
            ]);
            curl_multi_add_handle($mh, $ch);
            $handles[] = $ch;
        }

        do {
            $status = curl_multi_exec($mh, $active);
            if ($active) {
                curl_multi_select($mh, 0.05);
            }
        } while ($active && $status === CURLM_OK);

        foreach ($handles as $ch) {
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $totalTimeMs = (float) curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000.0;
            $latenciesMs[] = $totalTimeMs;
            if ($httpCode !== 200) {
                $errors++;
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
        $sent += $batchSize;
    }

    $wallSec = max(0.001, microtime(true) - $totalStart);
    sort($latenciesMs);
    $count = count($latenciesMs);
    $avgMs = array_sum($latenciesMs) / max(1, $count);
    $p95Idx = (int) floor(0.95 * ($count - 1));
    $p95Ms = $latenciesMs[$p95Idx] ?? 0.0;
    $maxMs = $latenciesMs[$count - 1] ?? 0.0;
    $rps = $count / $wallSec;
    $errorRate = ($errors / max(1, $count)) * 100.0;

    printf(
        "%s | Requests=%d Concurrency=%d | Avg=%.2f ms | P95=%.2f ms | Max=%.2f ms | RPS=%.2f | Errors=%d (%.2f%%)" . PHP_EOL,
        $tier['name'],
        $count,
        $tier['concurrency'],
        $avgMs,
        $p95Ms,
        $maxMs,
        $rps,
        $errors,
        $errorRate
    );
}
