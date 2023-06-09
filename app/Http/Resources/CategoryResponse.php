<?php

namespace App\Http\Resources;

use App\Traits\StatusCodeResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResponse extends JsonResource
{
    use StatusCodeResponseTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => url($this->image),
            'thumbnail' => url($this->thumbnail),
            'created_at' => $this->created_at?->toIsoString(),
            'updated_at' => $this->updated_at?->toIsoString(),
        ];
    }
}
