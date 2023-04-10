<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'partida_id',
        'nombre',
        'image',
        'presentacion',
        'condicion',
        'sucursal_id',
        'deleted_at'
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
