<?php

namespace AwesIO\Auth\Controllers\Traits;

trait RedirectsTo
{
    protected $redirectMappings = [
        'AwesIO\Auth\Controllers\LoginController' => 'login'
    ];

    protected function redirectTo()
    {
        return config('awesio-auth.redirects.' . $this->redirectMappings[get_class($this)])
            ?: (property_exists($this, 'redirectTo') ? $this->redirectTo : '/');
    }
}
