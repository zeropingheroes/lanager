<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_filename',
    ];

    /**
     * Users who have been awarded the achievement
     */
    public function users(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserAchievement');
    }
}
