<?php

namespace AwesIO\Auth\Tests\Stubs;

use AwesIO\Auth\Services\Contracts\TwoFactor as TwoFactorInterface;

class TwoFactor implements TwoFactorInterface
{
    /**
     * Http client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        //
    }

    /**
     * Register user on Authy
     *
     * @param \App\User $user
     * @return boolean|object
     */
    public function register($user)
    {
        return false;
    }

    /**
     * Verify if 2FA token is valid
     *
     * @param \App\User $user
     * @param string $token
     * @return boolean
     */
    public function verifyToken($user, $token)
    {
        return true;
    }

    /**
     * Remove user from Authy
     *
     * @param \App\User $user
     * @return boolean
     */
    public function remove($user)
    {
        return true;
    }

    /**
     * Request QR code link.
     *
     * @param string $authy_id User's id stored in your database
     * @param array  $opts     Array of options, for example: array("qr_size" => 300)
     *
     * @return mixed
     */
    public function qrCode($user)
    {
        return json_decode([]);
    }
}
