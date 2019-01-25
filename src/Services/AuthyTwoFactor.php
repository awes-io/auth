<?php

namespace AwesIO\Auth\Services;

use App\User;
use GuzzleHttp\Client;
use AwesIO\Auth\Services\Contracts\TwoFactor;

class AuthyTwoFactor implements TwoFactor
{
    /**
     * Http client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Register user on Authy
     *
     * @param \App\User $user
     * @return boolean|object
     */
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

    /**
     * Verify if 2FA token is valid
     *
     * @param \App\User $user
     * @param string $token
     * @return boolean
     */
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

    /**
     * Remove user from Authy
     *
     * @param \App\User $user
     * @return boolean
     */
    public function remove(User $user)
    {
        try {
            $response = $this->client->request(
                'POST',
                'https://api.authy.com/protected/json/users/delete/'
                    . $user->twoFactor->identifier
                    . '?api_key=' . config('awesio-auth.two_factor.authy.secret')
            );
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get data needed for user registration on Authy
     *
     * @param \App\User $user
     * @return array
     */
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
