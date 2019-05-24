<?php

namespace AwesIO\Auth\Repositories;

use AwesIO\Auth\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * Get existing user by social service data
     *
     * @param array $serviceUser
     * @param string $service
     * @return 
     */
    public function getUserBySocial($serviceUser, $service)
    {
        return getModelForGuard(config('auth.defaults.guard'))::where('email', $serviceUser->getEmail())
            ->orWhereHas('social', 
                function ($query) use ($serviceUser, $service) {
                    $query->where('social_id', $serviceUser->getId())->where('service', $service);
                }
            )->first();
    }

    /**
     * Create new user
     *
     * @param array $data
     * @return 
     */
    public function store(array $data)
    {
        return getModelForGuard(config('auth.defaults.guard'))::create($data);
    }
}