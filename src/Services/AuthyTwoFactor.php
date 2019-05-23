<?php

namespace AwesIO\Auth\Services;

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
     * @param $user
     * @return boolean|object
     */
    public function register($user)
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
     * @param $user
     * @param string $token
     * @return boolean
     */
    public function verifyToken($user, $token)
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
     * @param $user
     * @return boolean
     */
    public function remove($user)
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
     * Request QR code link.
     *
     * @param array  $user
     *
     * @return mixed
     */
    public function qrCode($user)
    {
        try {
            $response = $this->client->request(
                'POST',
                'https://api.authy.com/protected/json/users/'
                    . $user->twoFactor->identifier
                    . '/secret?api_key=' . config('awesio-auth.two_factor.authy.secret')
            );
        } catch (\Exception $e) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
     * Get data needed for user registration on Authy
     *
     * @param $user
     * @return array
     */
    protected function getUserRegistrationPayload($user)
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
