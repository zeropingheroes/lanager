<?php

declare(strict_types=1);

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
            'published' => (bool) $this->published,
            // `venue` is a nullable relation (unlike the other embeds below), so the closure form of
            // whenLoaded() is used to distinguish "not requested" (key omitted) from "requested, but
            // this LAN has none" (venue: null).
            'venue' => $this->whenLoaded('venue', fn () => $this->venue ? new VenueResource($this->venue) : null),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'events' => EventResource::collection($this->whenLoaded('events')),
            'slides' => SlideResource::collection($this->whenLoaded('slides')),
        ];
    }
}
