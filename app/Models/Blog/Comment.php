<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'fecha', 'nombre', 'correo', 'contenido', 'posts_id'
    ];
}
