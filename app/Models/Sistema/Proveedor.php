<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    
    protected $fillable = [
        'id', 'nombre'
    ];
}