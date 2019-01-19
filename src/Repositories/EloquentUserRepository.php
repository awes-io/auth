<?php

namespace AwesIO\Auth\Repositories;

// TODO: move User to auth package ???
use App\User;
use AwesIO\Auth\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * Get existing user
     *
     * @param array $serviceUser
     * @param string $service
     * @return \App\User
     */
    public function getUser($serviceUser, $service)
    {
        return User::where('email', $serviceUser->getEmail())
            ->orWhereHas('social', 
                function ($query) use ($serviceUser, $service) {
                    $query->where('social_id', $serviceUser->getId())->where('service', $service);
                }
            )->first();
    }

    public function store(array $data)
    {
        return User::create($data);
    }
}