<?php

namespace App\Console\Commands\Services;

use App\Traits\ConsumesExternalServices;

class RequestHelper
{
    use ConsumesExternalServices;

    protected string $baseUrl;

    protected string $jwtToken;

    public function __construct(string $baseUrl = '')
    {
        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }else{
            $this->baseUrl = env('APP_URL') . '/api';
        }
    }

    public function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'accept' => 'application/json'
        ];
        if (isset($this->jwtToken)) {
            $headers['Authorization'] = 'Bearer ' . $this->jwtToken;
        }

        return $headers;
    }

    public function setToken(string $token)
    {
        $this->jwtToken = $token;
    }

    public function buildUrl(string $path): string
    {
        return $this->baseUrl . $path;
    }
}
