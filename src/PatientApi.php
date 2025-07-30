<?php
require_once __DIR__ . '/HttpClient.php';

class PatientApi extends HttpClient {
    private string $token;

    public function __construct(string $token) {
        $this->token = $token;
    }

    private function authHeaders(): array {
        return [
            'Authorization: Bearer ' . $this->token,
            'Accept: application/json',
        ];
    }

    public function search(string $baseUrl): array {
        $q = http_build_query([
            'first' => 'Patricia',
            'last'  => 'Doe',
            'dob'   => '1955-03-02',
            'email' => 'patricia@doemail.com'
        ]);
        $url = rtrim($baseUrl, '/') . '/patients?' . $q;
        $response = $this->get($url, $this->authHeaders());
        
        // Extract records from response
        return $response['records'] ?? [];
    }

    public function getDetails(string $baseUrl, string $patientId): array {
        $url = rtrim($baseUrl, '/') . '/patients/' . $patientId;
        return $this->get($url, $this->authHeaders());
    }
}