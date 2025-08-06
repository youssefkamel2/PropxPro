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
            'description' => $this->description,
            'slug' => $this->slug,
            'date' => $this->date,
            // return the images url as https://api.propxpro.com/storage/webinars-events/FLtm4eEnJPzq9e51moRee4pGZNOXdFK4uZwZxrXu.png
            'cover_photo' => $this->cover_photo ? 'https://api.propxpro.com/storage/' . $this->cover_photo : null,
            'host_image' => $this->host_image ? 'https://api.propxpro.com/storage/' . $this->host_image : null,
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