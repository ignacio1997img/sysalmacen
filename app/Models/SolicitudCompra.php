<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model
{
    use HasFactory;

    protected $fillable = ['sucursal_id','direccionadministrativa', 'unidadadministrativa', 'modality_id', 'registeruser_id',
                            'nrosolicitud', 'fechaingreso', 'gestion', 'condicion', 'deleteuser_id','stock'
                            ];
                            
}
