<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class StrMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Str::macro('uniqueChars', function (string $str) {
            return count(array_unique(str_split($str)));
        });

        Str::macro('isPalindrome', function (string $str, int $start = null, int $end = null) {
            $start = $start ?? 0;
            $end = $end ?? Str::length($str) - 1;

            while ($start <= $end) {
                if ($str[$start] != $str[$end]) {
                    return false;
                }

                $start++;
                $end--;
            }

            return true;

            /**
             * Easier way, but harder when needs to be reused
             *
             * $revString = strrev($str);
             * return $revString == $str;
             */
        });
    }
}
