<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFavouriteGame extends JsonResource
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
            'game' => new Game($this->favouriteable),
        ];
    }
}
