<!-- < ?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Access token using $_ENV
$token = $_ENV['ARTIFACTORY_API_TOKEN'] ?? 'Token not found';
echo "API Token: " . $token . "\n";
?> -->

<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Service\ArtifactoryAuthService;
use GuzzleHttp\Client;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    // Instantiate the HTTP client and the authentication service
    $client = new Client();
    $authService = new ArtifactoryAuthService($client);

    // Fetch the access token
    $token = $authService->authenticate();

    echo "API Token: " . $token . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

?>