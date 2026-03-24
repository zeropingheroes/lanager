<?php

namespace Zeropingheroes\Lanager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zeropingheroes\Lanager\Models\Slide;

/**
 * @mixin Slide
 */
class SlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'position' => $this->position,
            'duration' => $this->duration,
            'lan' => new LanResource($this->whenLoaded('lan')),
            'links' => [
                'self' => route('api.lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
                'self_gui' => route('lans.slides.show', ['lan' => $this->lan_id, 'slide' => $this->id]),
            ],
        ];
    }
}
