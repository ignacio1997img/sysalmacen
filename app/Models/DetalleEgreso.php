<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
    use HasFactory;


    protected $fillable = [
        'solicitudegreso_id',
        'detallefactura_id',
        'registeruser_id',
        'cantsolicitada',
        'precio',
        'totalbs',
        'gestion',
        'condicion',
        'deleteuser_id',
        'sucursal_id'
    ];
    
}
