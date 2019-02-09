<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Slide extends JsonResource
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
            'content' => $this->content,
            'position' => $this->position,
            'lan' => new Lan($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.slides.show', $this->id),
                'self_gui' => route('lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
            ],
        ];
    }
}
