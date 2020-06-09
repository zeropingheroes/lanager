<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveGame extends JsonResource
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
            'game' => new Game($this->resource['game']),
            'users' => User::collection(collect($this->resource['users'])),
        ];
    }
}
