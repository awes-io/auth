<?php

namespace AwesIO\Auth\Repositories;

use App\User;
use AwesIO\Auth\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * Get existing user by social service data
     *
     * @param array $serviceUser
     * @param string $service
     * @return \App\User
     */
    public function getUserBySocial($serviceUser, $service)
    {
        return User::where('email', $serviceUser->getEmail())
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
     * @return \App\User
     */
    public function store(array $data)
    {
        return User::create($data);
    }
}