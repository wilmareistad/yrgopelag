<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

echo "<h1>Guzzle Test</h1>";

try {
    $client = new Client([
        'base_uri' => 'https://www.yrgopelag.se/centralbank/',
        'timeout' => 30,
        'connect_timeout' => 10,
        'verify' => true,
        'http_errors' => false
    ]);

    echo "<p>Guzzle client created successfully!</p>";

    // Test POST to transferCode
    $testCode = 'test123';
    $testCost = 100;

    $response = $client->post('transferCode', [
        'json' => [
            'transferCode' => $testCode,
            'totalCost' => $testCost
        ]
    ]);

    echo "<h2>Response:</h2>";
    echo "<pre>";
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Body: " . $response->getBody()->getContents() . "\n";
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
