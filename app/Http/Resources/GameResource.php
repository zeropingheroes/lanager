<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\SteamApp;

/**
 * @mixin SteamApp
 */
class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[\Override]
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
