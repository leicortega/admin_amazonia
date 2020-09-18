<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';

    protected $fillable = [ 'nombre' ];
}
