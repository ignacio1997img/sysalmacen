<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDetalle extends Model
{
    use HasFactory;

    protected $fillable =[
         'solicitud_id','solicitudcompra_id', 'detallefactura_id', 'cantidad', 'condicion', 'deleteuser_id'
    ];

}
