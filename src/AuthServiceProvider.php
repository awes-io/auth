<?php

namespace AwesIO\Auth;

use AwesIO\Auth\Auth;
use Illuminate\Support\ServiceProvider;
use AwesIO\Auth\Contracts\Auth as AuthContract;
use AwesIO\Auth\Services\SocialiteProvidersManager;
use AwesIO\Auth\Repositories\EloquentUserRepository;
use AwesIO\Auth\Repositories\Contracts\UserRepository;
use AwesIO\Auth\Services\Contracts\SocialProvidersManager;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'awesio-auth');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/awesio-auth.php' => config_path('awesio-auth.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/awesio-auth.php', 'awesio-auth');

        $this->app->singleton(AuthContract::class, Auth::class);

        $this->registerRepositories();

        $this->registerServices();
    }

    public function registerRepositories()
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function registerServices()
    {
        $this->app->bind(SocialProvidersManager::class, SocialiteProvidersManager::class);
    }
}
