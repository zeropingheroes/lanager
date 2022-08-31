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
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserAchievement');
    }
}
