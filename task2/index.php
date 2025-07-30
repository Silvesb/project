<?php

require_once __DIR__ . '/src/AuthClient.php';
require_once __DIR__ . '/src/PatientApi.php';
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Looking for .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiUrl = $_ENV['api_url'];
$appId = $_ENV['app_id'];
$clientId = $_ENV['client_id'];
$clientSecret = $_ENV['client_secret'];

$auth = new AuthClient();
$token = $auth->getToken("$apiUrl/token", $appId, $clientId, $clientSecret);
if (!$token) exit('Failed to get token');

$patientApi = new PatientApi($token);
$records = $patientApi->search($apiUrl);

if (count($records) !== 1) exit('No single match');

$patientId = $records[0]['pn'] ?? null; // Extract patient's ID (pn code) from search result
if (!$patientId) exit('Patient ID not found in records');

$patient = $patientApi->getDetails($apiUrl, $patientId);
print "<pre>";
print_r($patient);
print "</pre>";