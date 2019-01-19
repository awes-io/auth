<?php

namespace AwesIO\Auth\Repositories\Contracts;

interface UserRepository
{
    public function getUser($serviceUser, $service);

    public function store(array $data);
}