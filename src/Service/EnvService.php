<?php

namespace App\Service;

use Dotenv\Dotenv;

class EnvService
{
    private $dotenv;

    public function __construct()
    {
        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $this->dotenv->load();
    }

    public function setEnvVariable($key, $value)
    {
        $envFile = __DIR__ . '/../../.env';
        $envContent = file_get_contents($envFile);
        $envContent .= "\n$key=$value";
        file_put_contents($envFile, $envContent);
    }
}
