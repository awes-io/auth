<?php

namespace AwesIO\Auth\Models\Traits;

use AwesIO\Auth\Models\TwoFactor;

trait HasTwoFactorAuthentication
{
    /**
     * Define an \AwesIO\Auth\Models\TwoFactor relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twoFactor()
    {
        return $this->hasOne(TwoFactor::class);
    }
}
