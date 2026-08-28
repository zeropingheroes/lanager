<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\VenueFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $street_address
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Lan> $lans
 * @property-read int|null $lans_count
 *
 * @method static VenueFactory factory($count = null, $state = [])
 * @method static Builder<static>|Venue newModelQuery()
 * @method static Builder<static>|Venue newQuery()
 * @method static Builder<static>|Venue query()
 * @method static Builder<static>|Venue whereCreatedAt($value)
 * @method static Builder<static>|Venue whereDescription($value)
 * @method static Builder<static>|Venue whereId($value)
 * @method static Builder<static>|Venue whereName($value)
 * @method static Builder<static>|Venue whereStreetAddress($value)
 * @method static Builder<static>|Venue whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street_address',
    ];

    /**
     * LANs hosted at the venue
     */
    public function lans(): HasMany
    {
        return $this->hasMany(Lan::class);
    }
}
