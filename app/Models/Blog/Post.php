<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'fecha', 'titulo', 'slug', 'imagen', 'contenido', 'users_id'
    ];

    public function media_posts() {
        return $this->hasMany('App\Models\Blog\Media_post', 'posts_id');
    }

    public function comments() {
        return $this->hasMany('App\Models\Blog\Comment', 'posts_id');
    }

    public function users() {
        return $this->belongsTo('App\User');
    }
}
