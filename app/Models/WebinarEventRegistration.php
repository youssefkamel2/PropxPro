<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarEventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'company',
        'reason_for_attending',
        'phone',
    ];

    public function event()
    {
        return $this->belongsTo(WebinarEvent::class, 'event_id');
    }
}