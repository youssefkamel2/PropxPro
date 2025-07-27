<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'subcategories' => HelpSubcategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
} 