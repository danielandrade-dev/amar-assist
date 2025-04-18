<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ActiveStorageResource;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'cost' => $this->cost,
            'status' => $this->status,
            'slug' => $this->slug,
            'active_storage' => $this->whenLoaded('activeStorage', function () {
                return new ActiveStorageResource($this->activeStorage);
            }),
            'logs' => $this->whenLoaded('logs'),
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null,
        ];
    }
}
