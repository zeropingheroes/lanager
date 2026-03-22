<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'logo' => [
                'small' => $this->logo_small,
                'medium' => $this->logo_medium,
                'large' => $this->logo_large,
            ],
        ];
    }
}
