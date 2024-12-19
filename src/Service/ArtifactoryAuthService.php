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
            $token = trim(file_get_contents($this->tokenFile));
    
            if ($this->isTokenExpired($token)) {
                $this->authenticate($_ENV['ARTIFACTORY_USERNAME'], $_ENV['ARTIFACTORY_PASSWORD'], $_ENV['ARTIFACTORY_BASE_URL']);
                $token = trim(file_get_contents($this->tokenFile));
            }
    
            return $token;
        }
        throw new \Exception("No token found. Please login first.");
    }
    
    private function isTokenExpired($token)
    {
        $parts = explode('.', $token);
        $payload = json_decode(base64_decode($parts[1]), true);
    
        return isset($payload['exp']) && $payload['exp'] < time();
    }
    
    public function createUser(array $userData, $baseUrl)
    {
        $token = $this->getToken();
        $url = rtrim($baseUrl, '/') . '/artifactory/api/security/users/' . $userData['name'];
    
        try {
            $response = $this->client->put($url, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json'
                ],
                'json' => $userData
            ]);
    
            if ($response->getStatusCode() === 201) {
                return "User '{$userData['name']}' created successfully.";
            }
    
            throw new \Exception('User creation failed: ' . $response->getReasonPhrase());
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown';
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            throw new \Exception("User creation failed [HTTP {$statusCode}]: {$errorMessage}");
        }
    }

    public function deleteUser($username, $baseUrl)
    {
        $token = $this->getToken();
        $url = rtrim($baseUrl, '/') . '/artifactory/api/security/users/' . $username;
    
        try {
            $response = $this->client->delete($url, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json'
                ]
            ]);
    
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
    
            // Check if the response status and body confirm deletion
            if ($statusCode === 200 && strpos($responseBody, "has been removed successfully") !== false) {
                return "User '{$username}' deleted successfully.";
            }
    
            // If status code isn't 204 or response isn't as expected, throw an exception
            throw new \Exception("User deletion failed [HTTP {$statusCode}]: {$responseBody}");
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown';
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            throw new \Exception("User deletion failed [HTTP {$statusCode}]: {$errorMessage}");
        }
    }
    
    
}
?>
