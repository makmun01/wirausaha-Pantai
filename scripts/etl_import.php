<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Services/EtlMigrationService.php';

use App\Services\EtlMigrationService;

$options = getopt('', ['file::', 'apply', 'dry-run', 'dsn::', 'user::', 'pass::', 'json']);
$csvFile = $options['file'] ?? (__DIR__ . '/../database/samples/raw_umkm_survey.csv');
$apply = isset($options['apply']) && !isset($options['dry-run']);

$pdo = null;
if ($apply) {
    $dsn = $options['dsn'] ?? ('sqlite:' . sys_get_temp_dir() . '/jumiang_etl_staging.sqlite');
    $user = $options['user'] ?? null;
    $pass = $options['pass'] ?? null;
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if (str_starts_with($dsn, 'sqlite:')) {
        $pdo->exec('CREATE TABLE IF NOT EXISTS umkm_merchants (id INTEGER PRIMARY KEY AUTOINCREMENT, business_name TEXT)');
        $pdo->exec('CREATE TABLE IF NOT EXISTS map_markers (id INTEGER PRIMARY KEY AUTOINCREMENT, category TEXT, title TEXT, description TEXT, latitude REAL, longitude REAL, icon_type TEXT, is_active INTEGER DEFAULT 1)');
        $pdo->exec('CREATE TABLE IF NOT EXISTS migration_quarantine_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, source_file TEXT, row_number INTEGER, raw_payload TEXT, error_reason TEXT)');
    }
}

$service = new EtlMigrationService();
$report = $service->processUmkmSurveyCsv($csvFile, $pdo, !$apply);

if (isset($options['json'])) {
    echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit($report['zero_data_loss'] ? 0 : 1);
}

echo "=== JUMIANG ETL MIGRATION AUDIT REPORT ===" . PHP_EOL;
echo "Source File         : {$report['source_file']}" . PHP_EOL;
echo "Mode                : " . ($report['dry_run'] ? 'DRY-RUN' : 'APPLY (DATABASE)') . PHP_EOL;
echo "Extracted Rows      : {$report['extracted_rows']}" . PHP_EOL;
echo "Valid Transformed   : {$report['valid_transformed_rows']}" . PHP_EOL;
echo "Loaded Rows         : {$report['loaded_rows']}" . PHP_EOL;
echo "Quarantined Rows    : {$report['quarantined_rows']}" . PHP_EOL;
echo "Duplicates Skipped  : {$report['duplicates_skipped']}" . PHP_EOL;
echo "Zero Data Loss      : " . ($report['zero_data_loss'] ? 'PASS' : 'FAIL') . PHP_EOL;

exit($report['zero_data_loss'] ? 0 : 1);
