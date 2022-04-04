<?php

namespace App\Providers;

use App\Factory\GameMethodFactory;
use App\Services\Games\GameServiceInterface;
use App\Services\Games\WordGame\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            GameServiceInterface::class,
            function () {
                if (request()->has('game')) {
                    return GameMethodFactory::createService(request('game'));
                }
                return new Service();
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
