<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpSubcategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'topics' => HelpTopicResource::collection($this->whenLoaded('topics')),
        ];
    }
} 