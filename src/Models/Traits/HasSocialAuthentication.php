<?php

namespace AwesIO\Auth\Models\Traits;

use AwesIO\Auth\Models\UserSocial;

trait HasSocialAuthentication
{
    /**
     * Define an \AwesIO\Auth\Models\UserSocial relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function social()
    {
        return $this->hasMany(UserSocial::class);
    }

    /**
     * Check if has any \AwesIO\Auth\Models\UserSocial relationships.
     *
     * @param string $service
     * @return boolean
     */
    public function hasSocial($service)
    {
        return $this->social->where('service', $service)->count() > 0;
    }
}
