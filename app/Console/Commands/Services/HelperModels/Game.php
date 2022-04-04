<?php

namespace App\Console\Commands\Services\HelperModels;

use App\Console\Commands\Services\RequestHelper;

class Game extends RequestHelper
{

    public function getGames(array $query = [])
    {
        $url = $this->buildUrl('/game');

        return $this->makeRequest('get', $url, $query);
    }

    public function getGameFields(array $query = [])
    {
        $url = $this->buildUrl('/game/fields');

        return $this->makeRequest('get', $url, $query);
    }

    public function launchGame(array $body = [])
    {
        $url = $this->buildUrl('/game/play');

        return $this->makeRequest('post', $url, $body);
    }
}
