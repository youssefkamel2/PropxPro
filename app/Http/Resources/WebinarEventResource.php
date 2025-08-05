<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebinarEventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'date' => $this->date,
            'cover_photo' => $this->cover_photo,
            'duration' => $this->duration,
            'presented_by' => $this->presented_by,
            'created_by' => $this->created_by,
            'subscribers' => WebinarEventRegistrationResource::collection($this->registrations),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}