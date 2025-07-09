<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'url',
        'original_name',
        'mime_type',
        'size'
    ];
}
