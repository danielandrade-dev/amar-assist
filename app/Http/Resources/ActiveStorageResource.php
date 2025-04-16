<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActiveStorageResource extends JsonResource
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
            'uuid' => $this->uuid,
            'filename' => $this->filename,
            'service_name' => $this->service_name,
            'content_type' => $this->content_type,
            'product_id' => $this->product_id,
            'url' => $this->getUrl(),
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null,
        ];
    }
}
