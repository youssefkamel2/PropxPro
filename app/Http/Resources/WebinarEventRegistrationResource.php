<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebinarEventRegistrationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'company' => $this->company,
            'reason_for_attending' => $this->reason_for_attending,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
        ];
    }
}