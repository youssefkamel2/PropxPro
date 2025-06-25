<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'type', 'category',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'feature_plan')
            ->withPivot('value')
            ->withTimestamps();
    }
} 