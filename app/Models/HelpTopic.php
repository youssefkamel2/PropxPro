<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'title',
        'slug',
        'content',
        'headings',
        'order',
        'is_active',
        'created_by',
    ];

    public function subcategory()
    {
        return $this->belongsTo(HelpSubcategory::class, 'subcategory_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function setHeadingsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['headings'] = json_encode($value);
        } else {
            $this->attributes['headings'] = $value;
        }
    }

    public function getHeadingsAttribute($value)
    {
        if (!$value)
            return [];
        return json_decode($value, true) ?: [];
    }
}