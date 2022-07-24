<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Slide extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'position' => $this->position,
            'duration' => $this->duration,
            'lan' => new Lan($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
                'self_gui' => route('lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
            ],
        ];
    }
}
