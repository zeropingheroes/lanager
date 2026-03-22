<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OAuthAccount extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,
            'username' => $this->username,
            'avatar' => [
                'small' => str_replace('_medium.jpg', '.jpg', $this->avatar),
                'medium' => $this->avatar,
                'large' => str_replace('_medium.jpg', '_full.jpg', $this->avatar),
            ],
        ];
    }
}
