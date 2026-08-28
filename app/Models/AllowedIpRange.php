<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ip_range
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|AllowedIpRange newModelQuery()
 * @method static Builder<static>|AllowedIpRange newQuery()
 * @method static Builder<static>|AllowedIpRange query()
 * @method static Builder<static>|AllowedIpRange whereCreatedAt($value)
 * @method static Builder<static>|AllowedIpRange whereDescription($value)
 * @method static Builder<static>|AllowedIpRange whereId($value)
 * @method static Builder<static>|AllowedIpRange whereIpRange($value)
 * @method static Builder<static>|AllowedIpRange whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class AllowedIpRange extends Model
{
    protected $fillable = [
        'ip_range',
        'description',
    ];
}
