<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'content',
        'version',
        'status',
    ];

    /**
     * Scope to get the latest published document by type.
     */
    public function scopeLatestPublishedByType($query, $type)
    {
        return $query->where('type', $type)
            ->where('status', 'published')
            ->orderByDesc('version')
            ->first();
    }
} 