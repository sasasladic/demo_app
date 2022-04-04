<?php

namespace App\Providers;

use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Implementation\BaseRepository;
use App\Repositories\Implementation\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{

    /**
     * All the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        BaseRepositoryInterface::class => BaseRepository::class,
        UserRepositoryInterface::class => UserRepository::class
    ];

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
    }
}
