<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OAuthAccount extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,
            'username' => $this->username,
            'avatar' => [
                'small' => $this->avatarSmall(),
                'medium' => $this->avatarMedium(),
                'large' => $this->avatarLarge(),
            ],
        ];
    }
}
