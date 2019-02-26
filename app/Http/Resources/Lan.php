<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Lan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
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
