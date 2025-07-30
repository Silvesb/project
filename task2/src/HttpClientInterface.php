<?php

interface HttpClientInterface {
    public function get(string $url, array $headers = []): array;
    public function post(string $url, array $data, array $headers = []): array;
}