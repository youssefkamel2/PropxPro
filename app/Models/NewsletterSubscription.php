<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'is_active',
    ];

    public function emailLogs()
    {
        return $this->hasMany(NewsletterEmailLog::class, 'subscriber_id');
    }
} 