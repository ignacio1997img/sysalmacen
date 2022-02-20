<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionEgresoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'donacionegreso_id', 'donacioningresodetalle_id', 'registeruser_id', 'cantentregada', 'condicion', 'deleteuser_id'
    ];



}
