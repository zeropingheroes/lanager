<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveGame extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'game' => new Game($this->resource['game']),
            'users' => User::collection(collect($this->resource['users'])),
        ];
    }
}
