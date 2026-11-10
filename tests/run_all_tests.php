<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$unitOnly = in_array('--unit-only', $argv, true);
$externalServer = in_array('--external-server', $argv, true);
$baseUrl = getenv('JUMIANG_TEST_BASE_URL') ?: 'http://127.0.0.1:18085';

$testClasses = [
    \Tests\Unit\FinancialBookkeepingUnitTest::class,
    \Tests\Unit\OrderStateTransitionUnitTest::class,
    \Tests\Unit\EtlMigrationUnitTest::class,
];

$featureClasses = [
    \Tests\Feature\AuthAndRbacTest::class,
    \Tests\Feature\BlackBoxEpBvaTest::class,
    \Tests\Feature\ApiAndIntegrationTest::class,
    \Tests\Feature\SecurityOwaspTest::class,
];

$serverProc = null;

if (!$unitOnly) {
    $testClasses = array_merge($testClasses, $featureClasses);

    if (!$externalServer) {
        $publicDir = realpath(__DIR__ . '/../public') ?: (__DIR__ . '/../public');
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'jumiang_test_server.log', 'a'],
            2 => ['file', sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'jumiang_test_server.log', 'a'],
        ];
        $hostPort = parse_url($baseUrl, PHP_URL_HOST) . ':' . (parse_url($baseUrl, PHP_URL_PORT) ?: 18085);
        $serverProc = proc_open([PHP_BINARY, '-S', $hostPort, '-t', $publicDir], $descriptors, $pipes);

        if (is_resource($serverProc)) {
            $ready = false;
            for ($i = 0; $i < 40; $i++) {
                usleep(100000);
                $ch = curl_init(rtrim($baseUrl, '/') . '/health');
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 1,
                    CURLOPT_CONNECTTIMEOUT => 1,
                ]);
                $res = curl_exec($ch);
                $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($res !== false && $code === 200) {
                    $ready = true;
                    break;
                }
            }
            if (!$ready) {
                fwrite(STDERR, "WARNING: Server uji lokal {$baseUrl} belum merespons dalam 4 detik." . PHP_EOL);
            }
        }
    }
}

$total = 0;
$passed = 0;
$failed = 0;
$startTime = microtime(true);

echo "=== JUMIANG SMART TOURISM & UMKM AUTOMATED TEST SUITE ===" . PHP_EOL;
echo "PHP Version : " . PHP_VERSION . PHP_EOL;
echo "Mode        : " . ($unitOnly ? 'UNIT ONLY' : "FULL SUITE ({$baseUrl})") . PHP_EOL . PHP_EOL;

try {
    foreach ($testClasses as $class) {
        $reflection = new ReflectionClass($class);
        $setUpMethod = $reflection->hasMethod('setUp') ? $reflection->getMethod('setUp') : null;
        if ($setUpMethod !== null) {
            $setUpMethod->setAccessible(true);
        }

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!str_starts_with($method->getName(), 'test_')) {
                continue;
            }

            $total++;
            $instance = $reflection->newInstance();
            $testLabel = "{$reflection->getShortName()}::{$method->getName()}";

            try {
                if ($setUpMethod !== null) {
                    $setUpMethod->invoke($instance);
                }
                $method->invoke($instance);
                $passed++;
                echo "[PASS] {$testLabel}" . PHP_EOL;
            } catch (Throwable $e) {
                $failed++;
                echo "[FAIL] {$testLabel} -> {$e->getMessage()}" . PHP_EOL;
            }
        }
    }
} finally {
    if (is_resource($serverProc)) {
        proc_terminate($serverProc);
        proc_close($serverProc);
    }
}

$elapsedMs = round((microtime(true) - $startTime) * 1000, 2);

echo PHP_EOL . "------------------------------------------------------------" . PHP_EOL;
echo "Summary: Total={$total} | Passed={$passed} | Failed={$failed} | Time={$elapsedMs} ms" . PHP_EOL;

exit($failed === 0 ? 0 : 1);
