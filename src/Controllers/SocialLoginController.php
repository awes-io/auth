<?php

namespace AwesIO\Auth\Controllers;

use Socialite;
use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class SocialLoginController extends Controller
{
    /**
     * Redirect to OAuth provider authentication page
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function redirect(Request $request, $service)
    {
        return $this->buildProvider($service)->redirect();
    }

    /**
     * Obtain the user information from OAuth provider
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function callback(Request $request, $service)
    {     
        $user = $this->buildProvider($service)->user();

        dd($user);
    }

    /**
     * Build specific socialite provider
     *
     * @param string $service
     * @return void
     */
    protected function buildProvider($service)
    {
        return Socialite::buildProvider(
            $this->providerClassName(ucfirst($service)), 
            $this->getConfig($service)
        );
    }

    /**
     * Generate provider full class name
     *
     * @param string $prefix
     * @return string
     */
    protected function providerClassName($prefix)
    {
        return "\Laravel\Socialite\Two\\{$prefix}Provider";
    }

    /**
     * Get provider credentials from package config
     *
     * @param string $service
     * @return array
     */
    protected function getConfig($service)
    {
        return config('awesio-auth.socialite.' . $service);
    } 
}
