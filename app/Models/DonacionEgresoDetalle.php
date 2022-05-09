<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DonacionEgresoDetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'donacionegreso_id', 'donacioningresodetalle_id', 'registeruser_id', 'cantentregada', 'condicion', 'deleteuser_id', 'estado', 'caracteristica'
    ];


}
