<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\SteamApp;

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
        if ($this->resource instanceof SteamApp) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'url' => $this->steamStoreURL(),
                'images' => [
                    'large' => $this->image('large'),
                    'medium' => $this->image('medium'),
                    'small' => $this->image('small'),
                ],
            ];
        }
        return parent::toArray($request);
    }
}
