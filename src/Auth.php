<?php

namespace AwesIO\Auth;

use Illuminate\Database\Eloquent\Model;
use AwesIO\Auth\Contracts\Auth as AuthContract;
use AwesIO\Auth\Middlewares\SocialAuthentication;

class Auth implements AuthContract
{
    /**
     * Register routes for an application.
     *
     * @return void
     */
    public static function routes()
    {
        $router = app('router');

        // Authentication Routes...
        $router->get('login', '\AwesIO\Auth\Controllers\LoginController@showLoginForm')->name('login');
        $router->post('login', '\AwesIO\Auth\Controllers\LoginController@login');
        // $router->post('logout', 'Auth\LoginController@logout')->name('logout');

        // Registration Routes...
        $router->get('register', '\AwesIO\Auth\Controllers\RegisterController@showRegistrationForm')->name('register');
        $router->post('register', '\AwesIO\Auth\Controllers\RegisterController@register');

        // Socialite authentication Routes...
        $router->middleware(['guest', SocialAuthentication::class])->group(function () use ($router) {
            $router->get('login/{service}', '\AwesIO\Auth\Controllers\SocialLoginController@redirect')->name('login.social');
            $router->get('login/{service}/callback', '\AwesIO\Auth\Controllers\SocialLoginController@callback');
        });
    }
}
