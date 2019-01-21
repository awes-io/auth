<?php

namespace AwesIO\Auth\Services\Contracts;

use App\User;

interface TwoFactor
{
    public function register(User $user);

    public function verifyToken(User $user, $token);

    public function remove(User $user);

    // public function sendSMS(User $user);
}