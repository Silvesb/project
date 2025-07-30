<?php

require_once __DIR__ . '/HttpClientInterface.php';

class HttpClient implements HttpClientInterface {
    public function get(string $url, array $headers = []): array {
        return $this->request('GET', $url, [], $headers);
    }

    public function post(string $url, array $data, array $headers = []): array {
        return $this->request('POST', $url, $data, $headers);
    }

    protected function request(string $method, string $url, array $data, array $headers): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CAINFO => __DIR__ . '/cacert.pem', // SSL verification
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $res = curl_exec($ch);
        if ($res === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("cURL error: $error");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException("HTTP $httpCode: $res");
        }

        $data = json_decode($res, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decode error: " . json_last_error_msg());
        }
        
        return $data;
    }
}