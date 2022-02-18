<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDerivada extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_id', 'de_id', 'de_nombre', 'dirigido_id', 'dirigido_nombre', 'detalles',
        'rechazado', 'aprobado', 'atendido', 'condicion', 'deleteuser_id', 'fechapr'
    ];
}
