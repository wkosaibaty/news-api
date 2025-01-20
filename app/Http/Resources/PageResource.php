<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    protected string $resourceClass;

    public function __construct($resource, $resourceClass) {
        $this->resourceClass = $resourceClass;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'page' => $this->currentPage(),
            'page_size' => $this->perPage(),
            'records_count' => $this->total(),
            'records' => $this->resourceClass::collection($this->items()),
        ];
    }
}
