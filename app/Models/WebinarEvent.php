<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'cover_photo',
        'duration',
        'presented_by',
        'created_by',
    ];

    public function registrations()
    {
        return $this->hasMany(WebinarEventRegistration::class, 'event_id');
    }
}