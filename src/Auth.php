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
     * Register routes.
     *
     * @return void
     */
    public function routes()
    {
        // Authentication Routes...
        $this->loginRoutes();

        // Registration Routes...
        $this->registrationRoutes();

        // Reset password Routes...
        $this->resetPasswordRoutes();

        // Socialite authentication Routes...
        if ($this->isSocialEnabled()) {
            $this->socialiteRoutes();
        }

        // Two factor authentication Routes...
        if ($this->isTwoFactorEnabled()) {
            $this->twoFactorRoutes();
        }

        // Email verification Routes...
        if ($this->isEmailVerificationEnabled()) {
            $this->emailVerificationRoutes();
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
     * Check if two factor authentication eneabled in config
     *
     * @return boolean
     */
    public function isEmailVerificationEnabled()
    {
        return in_array('email_verification', config('awesio-auth.enabled'));
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

        $this->router->any(
            'logout',
            '\AwesIO\Auth\Controllers\LoginController@logout'
        )->name('logout');
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
     * Reset password routes.
     *
     * @return void
     */
    protected function resetPasswordRoutes()
    {
        $this->router->get(
            'password/reset',
            '\AwesIO\Auth\Controllers\ForgotPasswordController@showLinkRequestForm'
        )->name('password.request');

        $this->router->post(
            'password/email',
            '\AwesIO\Auth\Controllers\ForgotPasswordController@sendResetLinkEmail'
        )->name('password.email');

        $this->router->get(
            'password/reset/{token}',
            '\AwesIO\Auth\Controllers\ResetPasswordController@showResetForm'
        )->name('password.reset');

        $this->router->post(
            'password/reset',
            '\AwesIO\Auth\Controllers\ResetPasswordController@reset'
        )->name('password.update');
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
        // Setting up and verifying 2FA routes
        $this->router->middleware(['auth'])
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

                $this->router->delete(
                    'twofactor',
                    '\AwesIO\Auth\Controllers\TwoFactorController@destroy'
                )->name('twofactor.destroy');
            });

        // 2FA login routes
        $this->router->middleware(['guest'])
            ->group(function () {

                $this->router->get(
                    'login/twofactor/verify',
                    '\AwesIO\Auth\Controllers\TwoFactorLoginController@index'
                )->name('login.twofactor.index');

                $this->router->post(
                    'login/twofactor/verify',
                    '\AwesIO\Auth\Controllers\TwoFactorLoginController@verify'
                )->name('login.twofactor.verify');
            });
    }

    /**
     * Email verification routes.
     *
     * @return void
     */
    protected function emailVerificationRoutes()
    {
        $this->router->get(
            'email/verify', 
            '\AwesIO\Auth\Controllers\VerificationController@show'
        )->name('verification.code');

        $this->router->post(
            'email/verify', 
            '\AwesIO\Auth\Controllers\VerificationController@verifyCode'
        )->name('verification.code.verify');

        $this->router->get(
            'email/verify/{id}', 
            '\AwesIO\Auth\Controllers\VerificationController@verify'
        )->name('verification.verify');

        $this->router->get(
            'email/resend', 
            '\AwesIO\Auth\Controllers\VerificationController@resend'
        )->name('verification.resend');
    }
}
