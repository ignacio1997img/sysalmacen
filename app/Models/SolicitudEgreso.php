<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEgreso extends Model
{
    use HasFactory;
    protected $fillable = [
        'sucursal_id',
        'inventarioAlmacen_id',
        'direccionadministrativa',
        'unidadadministrativa',
        'registeruser_id',
        'nropedido',
        'fechasolicitud',
        'fechaegreso',
        'gestion',
        'condicion',
        'deleteuser_id',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
