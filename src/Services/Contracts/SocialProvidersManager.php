<?php

namespace AwesIO\Auth\Services\Contracts;

interface SocialProvidersManager
{
    public function buildProvider($service);
}