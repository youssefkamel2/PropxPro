<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'monthly_price', 'annual_price', 'description',
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_plan')
            ->withPivot('value')
            ->withTimestamps();
    }
} 