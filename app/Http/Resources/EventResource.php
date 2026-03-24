<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\Event;

/**
 * @mixin Event
 */
class EventResource extends JsonResource
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
            'description' => $this->description,
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'lan' => new LanResource($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.events.show', $this->id),
                'self_gui' => route('lans.events.show', ['lan' => $this->lan_id, 'event' => $this->id]),
            ],
        ];
    }
}
