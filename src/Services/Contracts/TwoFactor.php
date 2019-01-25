<?php

namespace AwesIO\Auth\Services\Contracts;

use App\User;

interface TwoFactor
{
    /**
     * Register user on 2FA service
     *
     * @param \App\User $user
     * @return boolean|object
     */
    public function register(User $user);

    /**
     * Verify if 2FA token is valid
     *
     * @param \App\User $user
     * @param string $token
     * @return boolean
     */
    public function verifyToken(User $user, $token);

    /**
     * Remove user from 2FA service
     *
     * @param \App\User $user
     * @return boolean
     */
    public function remove(User $user);

    // public function sendSMS(User $user);
}