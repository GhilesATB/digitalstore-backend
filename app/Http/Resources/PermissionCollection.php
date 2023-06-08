<?php

namespace App\Http\Resources;

use App\Traits\StatusCodeResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    use StatusCodeResponseTrait;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = PermissionResponse::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
