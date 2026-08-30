<?php

declare(strict_types=1);

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
            'start' => $this->start->timezone(config('app.timezone'))->toIso8601String(),
            'end' => $this->end->timezone(config('app.timezone'))->toIso8601String(),
            'timezone' => config('app.timezone'),
            'lan' => new LanResource($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.v1.lans.events.show', ['lan' => $this->lan_id, 'event' => $this->id]),
                'self_gui' => route('lans.events.show', ['lan' => $this->lan_id, 'event' => $this->id]),
            ],
        ];
    }
}
