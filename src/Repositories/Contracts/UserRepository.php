<?php

namespace AwesIO\Auth\Repositories\Contracts;

interface UserRepository
{
    public function getUserBySocial($serviceUser, $service);

    public function store(array $data);
}