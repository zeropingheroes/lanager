<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
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
            'name' => $this->name,
            'id' => $this->id,
            'id_type' => $this->id_type,
            'url' => $this->url(),
            'logo' => [
                'small' => $this->logo('small'),
                'medium' => $this->logo('medium'),
                'large' => $this->logo('large'),
            ],
        ];
    }
}
