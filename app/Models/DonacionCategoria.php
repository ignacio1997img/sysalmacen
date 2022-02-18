<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionCategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'condicion'
    ];

    public function DonacionArticulo()
    {
        return $this->hasMany(DonacionArticulo::class);
    }
}
