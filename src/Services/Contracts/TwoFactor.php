<?php

namespace AwesIO\Auth\Services\Contracts;

interface TwoFactor
{
    /**
     * Register user on 2FA service
     *
     * @param $user
     * @return boolean|object
     */
    public function register($user);

    /**
     * Verify if 2FA token is valid
     *
     * @param $user
     * @param string $token
     * @return boolean
     */
    public function verifyToken($user, $token);

    /**
     * Remove user from 2FA service
     *
     * @param $user
     * @return boolean
     */
    public function remove($user);

    /**
     * Request QR code link.
     *
     * @param array  $user
     *
     * @return mixed
     */
    public function qrCode($user);

    // public function sendSMS(User $user);
}