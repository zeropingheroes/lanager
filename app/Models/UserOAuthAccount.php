<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\UserOAuthAccountFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $username
 * @property string $provider
 * @property string $provider_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $access_token
 * @property string|null $token_expiry
 * @property string|null $refresh_token
 * @property-read User $user
 *
 * @method static UserOAuthAccountFactory factory($count = null, $state = [])
 * @method static Builder<static>|UserOAuthAccount newModelQuery()
 * @method static Builder<static>|UserOAuthAccount newQuery()
 * @method static Builder<static>|UserOAuthAccount query()
 * @method static Builder<static>|UserOAuthAccount whereAccessToken($value)
 * @method static Builder<static>|UserOAuthAccount whereAvatar($value)
 * @method static Builder<static>|UserOAuthAccount whereCreatedAt($value)
 * @method static Builder<static>|UserOAuthAccount whereId($value)
 * @method static Builder<static>|UserOAuthAccount whereProvider($value)
 * @method static Builder<static>|UserOAuthAccount whereProviderId($value)
 * @method static Builder<static>|UserOAuthAccount whereRefreshToken($value)
 * @method static Builder<static>|UserOAuthAccount whereTokenExpiry($value)
 * @method static Builder<static>|UserOAuthAccount whereUpdatedAt($value)
 * @method static Builder<static>|UserOAuthAccount whereUserId($value)
 * @method static Builder<static>|UserOAuthAccount whereUsername($value)
 *
 * @mixin Eloquent
 */
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
        return $this->belongsTo(User::class);
    }
}
