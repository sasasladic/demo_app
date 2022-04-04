<?php

namespace App\Console\Commands\Services\HelperModels;

use App\Console\Commands\Services\RequestHelper;

class User extends RequestHelper
{

    public function getGameHistory(array $query = [])
    {
        $url = $this->buildUrl('/user/history');

        return $this->makeRequest('get', $url, $query);
    }
}
