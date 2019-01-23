<?php

namespace AwesIO\Auth\Controllers\Traits;

trait RedirectsTo
{
    /**
     * Mappings between classes and config's keys
     *
     * @var array
     */
    protected $redirectMappings = [
        'AwesIO\Auth\Controllers\LoginController' => 'login',
        'AwesIO\Auth\Controllers\RegisterController' => 'register',
        'AwesIO\Auth\Controllers\ResetPasswordController' => 'reset_password',
        'AwesIO\Auth\Controllers\SocialLoginController' => 'social_login',
        'AwesIO\Auth\Controllers\TwoFactorLoginController' => 'twofactor_login',
    ];

    /**
     * Redirect to path which set in config or to default one
     *
     * @return string
     */
    protected function redirectTo()
    {
        return config('awesio-auth.redirects.' . $this->redirectMappings[get_class($this)])
            ?: (property_exists($this, 'redirectTo') ? $this->redirectTo : '/');
    }
}
