<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterEmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'subscriber_id',
        'status',
        'error_message',
        'sent_at',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscription::class, 'subscriber_id');
    }
} 