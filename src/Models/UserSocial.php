<?php

namespace AwesIO\Auth\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_social';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_id', 'service',
    ];

    /**
     * Define an inverse \App\User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
