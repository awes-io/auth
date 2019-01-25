<?php

namespace AwesIO\Auth\Facades;

use AwesIO\Auth\Contracts\Auth as AuthContract;
use Illuminate\Support\Facades\Facade;

class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthContract::class;
    }
}
