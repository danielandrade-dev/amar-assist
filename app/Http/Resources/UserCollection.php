<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => $this->meta(),
        ];
    }
    public function meta()
    {
        return [
            'total' => $this->collection->count(),
            'current_page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'last_page' => $this->resource->lastPage(),
            'total_items' => $this->resource->total(),
        ];
    }
}
