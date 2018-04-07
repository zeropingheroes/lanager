<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class LinkedAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];

    /**
     * Get the user who owns the account
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

}
