<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ArtifactoryAuthService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function authenticate($username, $password)
    {
        $url = 'https://your-artifactory-instance.com/artifactory/api/security/token';

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
            throw new \Exception('Authentication failed: ' . $e->getMessage());
        }
    }
}
