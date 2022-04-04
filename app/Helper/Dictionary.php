<?php

namespace App\Helper;

use App\Console\Commands\Services\RequestHelper;

class Dictionary
{
    public static function wordExists($word)
    {
        $req = new RequestHelper("https://api.dictionaryapi.dev/api/v2");
        $url = $req->buildUrl("/entries/en/$word");

        $response =  $req->makeRequest('get', $url, []);

        return $response->success;
    }
}
