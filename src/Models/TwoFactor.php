<?php

namespace AwesIO\Auth\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'two_factor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier', 'phone', 'dial_code', 'verified'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($twoFactor) {
            optional($twoFactor->user->twoFactor)->delete();
        });
    }

    /**
     * Define an inverse \App\User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if two factor auth is verified
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }
}
