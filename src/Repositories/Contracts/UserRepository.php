<?php

namespace AwesIO\Auth\Repositories\Contracts;

interface UserRepository
{
    /**
     * Get existing user by social service data
     *
     * @param array $serviceUser
     * @param string $service
     * @return \App\User
     */
    public function getUserBySocial($serviceUser, $service);

    /**
     * Create new user
     *
     * @param array $data
     * @return \App\User
     */
    public function store(array $data);
}