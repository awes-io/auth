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
        try {
            $response = $this->client->request(
                'POST',
                'https://api.authy.com/protected/json/users/new?api_key=' 
                    . config('awesio-auth.two_factor.authy.secret'), [
                    'form_params' => $this->getUserRegistrationPayload($user)
                ]
            );
        } catch (\Exception $e) {
            return false;
        }
        return json_decode($response->getBody());
    }

    public function verifyToken(User $user, $token)
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.authy.com/protected/json/verify/'
                    . $token . '/' . $user->twoFactor->identifier
                    . '?force=true&api_key=' . config('awesio-auth.two_factor.authy.secret')
            );
        } catch (\Exception $e) {
            return false;
        }
        
        $response = json_decode($response->getBody());

        return $response->token === 'is valid';
    }

    public function remove(User $user)
    {
        //
    }

    protected function getUserRegistrationPayload(User $user)
    {
        return [
            'user' => [
                'email' => $user->email,
                'cellphone' => $user->twoFactor->phone,
                'country_code' => $user->twoFactor->dial_code
            ]
        ];
    }
}
