<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 *
 * @method static Builder<static>|SteamUserStatusCode newModelQuery()
 * @method static Builder<static>|SteamUserStatusCode newQuery()
 * @method static Builder<static>|SteamUserStatusCode query()
 * @method static Builder<static>|SteamUserStatusCode whereDisplayName($value)
 * @method static Builder<static>|SteamUserStatusCode whereId($value)
 * @method static Builder<static>|SteamUserStatusCode whereName($value)
 *
 * @mixin Eloquent
 */
class SteamUserStatusCode extends Model
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
