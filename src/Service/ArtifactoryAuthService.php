<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ArtifactoryAuthService
{
    private $client;
    private $tokenFile;

    public function __construct(Client $client, string $tokenFile = __DIR__ . '/../../.token')
    {
        $this->client = $client;
        $this->tokenFile = $tokenFile;
    }

    public function authenticate($username, $password, $baseUrl)
    {
        $url = rtrim($baseUrl, '/') . '/artifactory/api/security/token';

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
                $this->storeToken($data['access_token']);
                return $data['access_token'];
            } else {
                throw new \Exception('Authentication failed: Invalid response from Artifactory API.');
            }
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown';
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody() : $e->getMessage();
            throw new \Exception("Authentication failed [HTTP {$statusCode}]: {$errorMessage}");
        }
    }

    private function storeToken($token)
    {
        file_put_contents($this->tokenFile, $token);
    }

    public function getToken()
    {
        if (file_exists($this->tokenFile)) {
            return trim(file_get_contents($this->tokenFile));
        }
        throw new \Exception("No token found. Please login first.");
    }

    public function createUser(array $userData)
    {
        $token = $this->getToken();
        $url = rtrim($baseUrl, '/') . '/artifactory/api/security/users';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json'
                ],
                'json' => $userData
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown';
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody() : $e->getMessage();
            throw new \Exception("User creation failed [HTTP {$statusCode}]: {$errorMessage}");
        }
    }
}

?>
