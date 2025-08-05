<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_url',
        'cover_photo',
        'type',
        'created_by',
    ];
}