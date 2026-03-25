<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class Slide extends Model
{
    protected $fillable = [
        'lan_id',
        'name',
        'content',
        'position',
        'duration',
        'start',
        'end',
        'published',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * LAN the slide is for
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }

    /**
     * Slides visible now
     */
    public function scopeVisibleNow(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where(function (Builder $query) {
                $query->whereNull('start')
                    ->whereNull('end');
            })->orWhere(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->whereNull('start')
                        ->orWhere('start', '<=', now());
                })->where(function (Builder $query) {
                    $query->whereNull('end')
                        ->orWhere('end', '>=', now());
                });
            });
        });
    }
}
