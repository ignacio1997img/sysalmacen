<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'razonsocial',
        'responsable',
        'direccion',
        'telefono',
        'fax',
        'comentario',
        'condicion',
        'sucursal_id',
        'deleted_at'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class. 'sucursal_id');
    }
}
