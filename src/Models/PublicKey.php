<?php

namespace Iwouldrathercode\Cognito\Models;

use Illuminate\Database\Eloquent\Model;

class PublicKey extends Model
{

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'kid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $fillable = [
        'kid',
        'public_key'
    ];
}
