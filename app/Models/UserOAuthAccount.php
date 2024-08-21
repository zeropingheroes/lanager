<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * User who owns the OAuth account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * Small avatar URL
     */
    public function avatarSmall(): string
    {
        if ($this->provider == 'steam') {
            return str_replace('_medium.jpg', '.jpg', $this->avatar);
        }

        return '';
    }

    /**
     * Medium avatar URL
     */
    public function avatarMedium(): string
    {
        if ($this->provider == 'steam') {
            return $this->avatar;
        }

        return '';
    }

    /**
     * Large avatar URL
     */
    public function avatarLarge(): string
    {
        if ($this->provider == 'steam') {
            return str_replace('_medium.jpg', '_full.jpg', $this->avatar);
        }

        return '';
    }
}
