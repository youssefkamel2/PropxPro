<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_photo',
        'category',
        'content',
        'mark_as_hero',
        'is_active',
        'created_by',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function emailLogs()
    {
        return $this->hasMany(NewsletterEmailLog::class);
    }

    public function getCoverPhotoUrlAttribute()
    {
        return $this->cover_photo ? asset('storage/' . $this->cover_photo) : null;
    }
} 