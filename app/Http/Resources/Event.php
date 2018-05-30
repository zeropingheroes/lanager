<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->name,
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'type' => new EventType($this->type),
            'lan' => new Lan($this->whenLoaded('lan')),
        ];
    }
}
