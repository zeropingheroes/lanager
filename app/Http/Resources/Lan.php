<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\Lan as LanModel;

/**
 * @mixin LanModel
 */
class Lan extends JsonResource
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
            'users' => User::collection($this->whenLoaded('users')),
            'events' => Event::collection($this->whenLoaded('events')),
            'slides' => Slide::collection($this->whenLoaded('slides')),
        ];
    }
}
