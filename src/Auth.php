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
        if ($this->isSocialEnabled()) {
            $this->socialiteRoutes();
        }

        // Two factor authentication Routes...
        if ($this->isTwoFactorEnabled()) {
            $this->twoFactorRoutes();
        }
    }

    /**
     * Check if socialite authentication eneabled in config
     *
     * @return boolean
     */
    public function isSocialEnabled()
    {
        return in_array('social', config('awesio-auth.enabled'));
    }

    /**
     * Check if two factor authentication eneabled in config
     *
     * @return boolean
     */
    public function isTwoFactorEnabled()
    {
        return in_array('two_factor', config('awesio-auth.enabled'));
    }

    /**
     * Login routes.
     *
     * @return void
     */
    protected function loginRoutes()
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
    protected function registrationRoutes()
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

    /**
     * Two factor routes.
     *
     * @return void
     */
    protected function twoFactorRoutes()
    {
        $this->router->middleware([])
            ->group(function () {

                $this->router->get(
                    'twofactor', 
                    '\AwesIO\Auth\Controllers\TwoFactorController@index'
                )->name('twofactor.index');

                $this->router->post(
                    'twofactor', 
                    '\AwesIO\Auth\Controllers\TwoFactorController@store'
                )->name('twofactor.store');

                $this->router->post(
                    'twofactor/verify', 
                    '\AwesIO\Auth\Controllers\TwoFactorController@verify'
                )->name('twofactor.verify');
            });
    }
}
