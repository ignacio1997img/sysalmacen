<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $fillable = ['unidadadministrativa', 'registeruser_id', 'cargo','sucursal_id', 'atendidopor',
                            'nroproceso', 'fechasolicitud', 'gestion', 'condicion', 'deleteuser_id', 'estado', 'derivado', 'cantidadentregar'
    ];

}
