<?php

namespace AwesIO\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];
}
