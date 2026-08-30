<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\Slide;

/**
 * @mixin Slide
 */
class SlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'position' => $this->position,
            'duration' => $this->duration,
            'start' => $this->start?->timezone(config('app.timezone'))->toIso8601String(),
            'end' => $this->end?->timezone(config('app.timezone'))->toIso8601String(),
            'published' => (bool) $this->published,
            'lan' => new LanResource($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.v1.lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
                'self_gui' => route('lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
            ],
        ];
    }
}
