<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalUnidadPrincipal extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id',
        'direccionAdministrativa_id',
        'unidadAdministrativa_id',
        'status',
        'registerUser_id',
        'deleteUser_id',
        'deleted_at'
    ];


    public function unidad()
    {
        return $this->belongsTo(Unit::class, 'unidadAdministrativa_id');
    }


}
