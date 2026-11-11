<?php

declare(strict_types=1);

namespace Tests;

class HttpClient
{
    private string $baseUrl;
    private ?string $cookieFile;

    public function __construct(?string $baseUrl = null, ?string $sessionName = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? (getenv('JUMIANG_TEST_BASE_URL') ?: 'http://127.0.0.1:18085'), '/');
        if ($sessionName !== null) {
            $safe = preg_replace('/[^a-zA-Z0-9_-]/', '_', $sessionName);
            $this->cookieFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "jumiang_cookie_{$safe}_" . getmypid() . '.txt';
            if (is_file($this->cookieFile)) {
                @unlink($this->cookieFile);
            }
        } else {
            $this->cookieFile = null;
        }
    }

    public function __destruct()
    {
        if ($this->cookieFile !== null && is_file($this->cookieFile)) {
            @unlink($this->cookieFile);
        }
    }

    public function get(string $path, array $headers = [], bool $followLocation = true): array
    {
        return $this->request('GET', $path, null, $headers, $followLocation);
    }

    public function postJson(string $path, array $payload, array $headers = []): array
    {
        $headers[] = 'Content-Type: application/json';
        return $this->request('POST', $path, json_encode($payload), $headers, false);
    }

    public function postForm(string $path, array $fields, array $headers = [], bool $followLocation = true): array
    {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        return $this->request('POST', $path, http_build_query($fields), $headers, $followLocation);
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = [], bool $followLocation = true): array
    {
        $ch = curl_init($this->baseUrl . $path);
        $respHeaders = [];

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => $followLocation,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADERFUNCTION => static function ($curl, string $headerLine) use (&$respHeaders): int {
                $len = strlen($headerLine);
                $parts = explode(':', $headerLine, 2);
                if (count($parts) === 2) {
                    $respHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
                return $len;
            },
        ];

        $upper = strtoupper($method);
        if ($upper === 'GET') {
            $options[CURLOPT_HTTPGET] = true;
        } elseif ($upper === 'POST') {
            $options[CURLOPT_POST] = true;
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = $upper;
        }

        curl_setopt_array($ch, $options);

        if ($this->cookieFile !== null) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        }

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $rawBody = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($rawBody === false) {
            throw new \RuntimeException("HTTP {$method} {$path} gagal: {$error}");
        }

        $decoded = json_decode($rawBody, true);

        return [
            'status' => $status,
            'headers' => $respHeaders,
            'body' => $rawBody,
            'json' => is_array($decoded) ? $decoded : null,
        ];
    }
}
