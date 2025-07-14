<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover_photo_url' => $this->cover_photo_url,
            'category' => $this->category,
            'content' => $this->content,
            // return is active as true or false
            'mark_as_hero' => $this->mark_as_hero ? true : false,
            'is_active' => $this->is_active ? true : false,
            'created_by' => $this->author->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 