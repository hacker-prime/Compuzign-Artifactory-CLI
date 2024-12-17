<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ArtifactoryAuthService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authenticate()
    {
        // Fetch credentials and URL from environment variables
        $username = $_ENV['ARTIFACTORY_USERNAME'];
        $password = $_ENV['ARTIFACTORY_PASSWORD'];
        $url = $_ENV['ARTIFACTORY_BASE_URL']. '/artifactory/api/security/token';

        if (!$username || !$password || !$url) {
            throw new \Exception('Environment variables for Artifactory are not properly set.');
        }

        try {
            $response = $this->client->post($url, [
                'auth' => [$username, $password],
                'form_params' => [
                    'username' => $username,
                    'scope' => 'member-of-groups:*',
                    'expires_in' => 3600
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['access_token'])) {
                return $data['access_token'];
            } else {
                throw new \Exception('Authentication failed: Invalid response from Artifactory API.');
            }
        } catch (RequestException $e) {
            // Detailed error handling
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown';
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody() : $e->getMessage();

            throw new \Exception("Authentication failed [HTTP {$statusCode}]: {$errorMessage}");
        }
    }
}
