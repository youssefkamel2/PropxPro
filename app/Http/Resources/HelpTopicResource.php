<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpTopicResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'headings' => $this->headings,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'name' => $this->author->name,
                    'bio' => $this->author->bio,
                    'profile_photo' => $this->author->profile_image ? asset('storage/' . $this->author->profile_image) : null,
                ];
            }),

            'category' => $this->subcategory->category ? [
                'id' => $this->subcategory->category->id,
                'name' => $this->subcategory->category->name,
            ] : null,

            'subcategory' => $this->subcategory ? [
                'id' => $this->subcategory->id,
                'name' => $this->subcategory->name,
            ] : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}