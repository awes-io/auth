<?php

namespace AwesIO\Auth\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    /**
     * Define an inverse User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
