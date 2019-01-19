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
        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('auth.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../views', 'awesio-auth');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthContract::class, Auth::class);

        $this->app->singleton('awesio-auth', AuthContract::class);
    }
}
