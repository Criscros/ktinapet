<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'tags',
        'images',
        'video_url',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
    ];
}
