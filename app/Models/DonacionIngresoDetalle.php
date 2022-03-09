<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionIngresoDetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        'donacioningreso_id', 'donacionarticulo_id', 'registeruser_id','estado','caracteristica', 'cantidad', 'precio', 'cantrestante', 'caducidad','condicion', 'deleteuser_id'      
    ];

}
