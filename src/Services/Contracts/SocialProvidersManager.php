<?php

namespace AwesIO\Auth\Services\Contracts;

interface SocialProvidersManager
{
    /**
     * Build specific socialite provider
     *
     * @param string $service
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($service);
}