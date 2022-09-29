<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalDireccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id', 'direccionAdministrativa_id', 'status', 'deleted_at'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
