<?php

namespace App\Console\Commands\Services\HelperModels;

use App\Console\Commands\Services\RequestHelper;

class Auth extends RequestHelper
{

    public function login(array $body)
    {
        $url = $this->buildUrl('/login');

        return $this->makeRequest('post', $url, $body);
    }

    public function register(array $body)
    {
        $url = $this->buildUrl('/register');

        return $this->makeRequest('post', $url, $body);
    }

}
