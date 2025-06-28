<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'monthly_price', 'annual_price', 'annual_savings', 'is_popular', 'description', 'is_active'
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_plan')
            ->withPivot('value')
            ->withTimestamps();
    }
} 