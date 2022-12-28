<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

/* @mixin Eloquent */
class UserOAuthAccount extends Model
{
    use HasFactory;

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

    protected $table = 'user_oauth_accounts';

    /**
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * @return string
     */
    public function avatarSmall(): string
    {
        if ($this->provider == 'steam') {
            return str_replace('_medium.jpg', '.jpg', $this->avatar);
        }

        return '';
    }

    /**
     * @return string
     */
    public function avatarMedium(): string
    {
        if ($this->provider == 'steam') {
            return $this->avatar;
        }

        return '';
    }

    /**
     * @return string
     */
    public function avatarLarge(): string
    {
        if ($this->provider == 'steam') {
            return str_replace('_medium.jpg', '_full.jpg', $this->avatar);
        }

        return '';
    }
}
