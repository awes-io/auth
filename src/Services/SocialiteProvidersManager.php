<?php

namespace AwesIO\Auth\Services;

use Socialite;
use AwesIO\Auth\Services\Contracts\SocialProvidersManager;

class SocialiteProvidersManager implements SocialProvidersManager
{
    /**
     * Build specific socialite provider
     *
     * @param string $service
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($service)
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