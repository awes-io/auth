<?php

namespace AwesIO\Auth\Models;

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
        //
    ];
}
