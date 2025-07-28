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
} 