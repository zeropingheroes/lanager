<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class UserOAuthAccount extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return string
     */
    public function avatarSmall(): string
    {
        if ($this->provider == 'steam') {
            return str_replace('_medium.jpg', '.jpg', $this->avatar);
        } elseif ($this->provider == 'discord') {
            return $this->avatar . '?size=64';
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
        } elseif ($this->provider == 'discord') {
            return $this->avatar . '?size=128';
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
        } elseif ($this->provider == 'discord') {
            return $this->avatar . '?size=512';
        }
        return '';
    }

    public function profileUrl(): string
    {
        if ($this->provider == 'steam') {
            return 'steam://url/SteamIDPage/' . $this->provider_id;
        }
        return '#';
    }

}
