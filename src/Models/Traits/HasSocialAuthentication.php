<?php

namespace AwesIO\Auth\Models\Traits;

use AwesIO\Auth\Models\UserSocial;

trait HasSocialAuthentication
{
    public function social()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function hasSocial($service)
    {
        return $this->social->where('service', $service)->count() > 0;
    }
}
