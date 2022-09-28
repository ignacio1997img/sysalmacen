<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalDirecion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sucursal_id', 'direccionAdministrativa_id', 'status', 'deleted_at'
    ];
}
