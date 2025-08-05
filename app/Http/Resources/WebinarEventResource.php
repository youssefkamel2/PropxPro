<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebinarEventResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'date' => $this->date,
            'cover_photo' => $this->cover_photo,
            'duration' => $this->duration,
            'presented_by' => $this->presented_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if (property_exists($this, 'showAdminFields') && $this->showAdminFields) {
            $data['created_by'] = $this->created_by;
            $data['subscribers'] = WebinarEventRegistrationResource::collection($this->registrations);
        }
        return $data;
    }
}