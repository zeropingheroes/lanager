<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\Lan;

/**
 * @mixin Lan
 */
class LanResource extends JsonResource
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
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'events' => EventResource::collection($this->whenLoaded('events')),
            'slides' => SlideResource::collection($this->whenLoaded('slides')),
        ];
    }
}
