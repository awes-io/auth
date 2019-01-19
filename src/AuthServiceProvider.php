<?php

namespace AwesIO\Auth;

use AwesIO\Auth\Contracts\Auth as AuthContract;
use AwesIO\Auth\Auth;
use Illuminate\Support\ServiceProvider;

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
    }
}
