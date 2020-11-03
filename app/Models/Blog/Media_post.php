<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Media_post extends Model
{
    protected $fillable = [
        'imagen', 'posts_id'
    ];
}
