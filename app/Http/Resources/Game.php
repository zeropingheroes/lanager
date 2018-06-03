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
                'logo' => [
                    'small' => $this->image('small'),
                    'medium' => $this->image('medium'),
                    'large' => $this->image('large'),
                ],
            ];
        }
        return parent::toArray($request);
    }
}
