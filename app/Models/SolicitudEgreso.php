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
        'deleted_at',
        'solicitudPedido_id',
        'subSucursal_id'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unit::class, 'unidadadministrativa');
    }

    public function direccion()
    {
        return $this->belongsTo(Direction::class, 'direccionadministrativa');
    }

    public function gestion()
    {
        return $this->belongsTo(InventarioAlmacen::class, 'inventarioAlmacen_id');
    }

    public function detalle()
    {
        return $this->hasMany(DetalleEgreso::class, 'solicitudegreso_id');
    }
}
