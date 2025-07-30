<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpTopicResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subcategory_id' => $this->subcategory_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'headings' => $this->headings,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}