<?php

require_once __DIR__ . '/HttpClient.php';

class AuthClient extends HttpClient {
    public function getToken(string $url, string $appId, string $clientId, string $clientSecret): string {
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ];
        
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'AppId: ' . $appId
        ];

        $response = $this->post($url, $data, $headers);
        return $response['access_token'] ?? null;
    }
}