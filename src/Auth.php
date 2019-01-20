<?php

namespace AwesIO\Auth;

use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Model;
use AwesIO\Auth\Contracts\Auth as AuthContract;
use AwesIO\Auth\Middlewares\SocialAuthentication;

class Auth implements AuthContract
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for an application.
     *
     * @return void
     */
    public function routes()
    {
        // Authentication Routes...
        $this->loginRoutes();

        // Registration Routes...
        $this->registrationRoutes();

        // Socialite authentication Routes...
        $this->socialiteRoutes();
    }

    /**
     * Login routes.
     *
     * @return void
     */
    public function loginRoutes()
    {
        $this->router->get(
            'login', 
            '\AwesIO\Auth\Controllers\LoginController@showLoginForm'
        )->name('login');

        $this->router->post(
            'login', 
            '\AwesIO\Auth\Controllers\LoginController@login'
        );

        // $router->post('logout', 'Auth\LoginController@logout')->name('logout');
    }

    /**
     * Registration routes.
     *
     * @return void
     */
    public function registrationRoutes()
    {
        $this->router->get(
            'register', 
            '\AwesIO\Auth\Controllers\RegisterController@showRegistrationForm'
        )->name('register');

        $this->router->post(
            'register', 
            '\AwesIO\Auth\Controllers\RegisterController@register'
        );
    }

    /**
     * Socialite routes.
     *
     * @return void
     */
    protected function socialiteRoutes()
    {
        $this->router->middleware(['guest', SocialAuthentication::class])
            ->group(function () {

                $this->router->get(
                    'login/{service}', 
                    '\AwesIO\Auth\Controllers\SocialLoginController@redirect'
                )->name('login.social');

                $this->router->get(
                    'login/{service}/callback', 
                    '\AwesIO\Auth\Controllers\SocialLoginController@callback'
                );
            });
    }
}
