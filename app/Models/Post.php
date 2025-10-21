<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'text_blogs';

    protected $fillable = [
        'title',
        'description',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
