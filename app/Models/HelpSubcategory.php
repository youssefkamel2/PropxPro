<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'order',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'category_id');
    }

    public function topics()
    {
        return $this->hasMany(HelpTopic::class, 'subcategory_id');
    }
} 