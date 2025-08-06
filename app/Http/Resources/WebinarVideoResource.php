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

        // If type is youtube, return the URL as is
        if ($this->type === 'youtube') {
            return $this->video_url;
        }

        // If type is upload, return the full asset URL
        if ($this->type === 'upload') {
            return asset('storage/' . $this->video_url);
        }

        // Fallback - return as asset URL
        return asset('storage/' . $this->video_url);
    }
}