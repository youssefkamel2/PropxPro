<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebinarVideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'video_url' => $this->getVideoUrl(),
            'cover_photo' => $this->cover_photo ? asset('storage/' . $this->cover_photo) : null,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getVideoUrl()
    {
        if (!$this->video_url) {
            return null;
        }

        if ($this->type === 'youtube') {
            return $this->video_url;
        }

        if ($this->type === 'upload') {
            return asset('storage/' . $this->video_url);
        }

        return asset('storage/' . $this->video_url);
    }
}