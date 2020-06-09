<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'lan' => new Lan($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.events.show', $this->id),
                'self_gui' => route('lans.events.show', ['lan' => $this->lan_id, 'event' => $this->id]),
            ],
        ];
    }
}
