<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturas_mantenimiento extends Model
{
    protected $fillable = [
        'proveedor', 'valor', 'factura_imagen', 'mantenimientos_id'
    ];

    public function mantenimientos() {
        return $this->belongsTo('App\Models\Mantenimiento');
    }
}
