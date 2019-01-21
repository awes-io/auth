<?php

namespace AwesIO\Auth\Services;

use AwesIO\Auth\Services\Contracts\TwoFactor;

class AuthyTwoFactor implements TwoFactor
{
    public function register(User $user)
    {
        //
    }

    public function verifyToken(User $user, $token)
    {
        //
    }

    public function remove(User $user)
    {
        //
    }
}
