<?php

namespace AwesIO\Auth\Services;

use App\User;
use GuzzleHttp\Client;
use AwesIO\Auth\Services\Contracts\TwoFactor;

class AuthyTwoFactor implements TwoFactor
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function register(User $user)
    {
        dd($user);
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
