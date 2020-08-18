<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id','tipo','permiso','url','mensaje','visto'
    ];
}
