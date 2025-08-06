<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'video_url',
        'cover_photo',
        'type',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = $model->generateSlug($model->title);
        });
        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = $model->generateSlug($model->title);
            }
        });
    }
    public function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $original = $slug;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}