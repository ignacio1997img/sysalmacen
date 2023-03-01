<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id',
        'fechasolicitud',
        'gestion',
        'nropedido',
        'people_id',
        'first_name',
        'last_name',
        'job',
        'direccion_name',
        'direccion_id',
        'unidad_name',
        'unidad_id',
        'visto',
        'status',
        'registerUser_Id',
        'deleted_at',
        'deletedUser_Id',
        'aprobadoUser_id',
        'aprobadoDate',
        'entregadoUser_id',
        'entregadoDate',
        'rechazadoUser_id',
        'rechazadoDate'

    ];


   

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    public function solicitudDetalle()
    {
        return $this->hasMany(SolicitudPedidoDetalle::class, 'solicitudPedido_id');
    }
    
}
