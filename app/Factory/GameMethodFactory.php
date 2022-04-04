<?php

namespace App\Factory;

use Illuminate\Support\Str;

class GameMethodFactory
{

    public static function createService($game)
    {
        $game = Str::camel($game);
        $class = 'App\\Services\\Games\\'. $game .'\\Service';
        if (class_exists($class)) {
            return new $class();
        }

        return null;
    }
}
