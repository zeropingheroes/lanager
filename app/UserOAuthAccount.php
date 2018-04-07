<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class UserOAuthAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'username',
        'avatar',
        'provider',
        'provider_id',
        'access_token',
        'token_expiry',
        'refresh_token',
    ];

    /**
     * The table that this model uses
     *
     * @var array
     */
    protected $table = 'user_oauth_accounts';

    /**
     * Get the user who owns the account
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

}
