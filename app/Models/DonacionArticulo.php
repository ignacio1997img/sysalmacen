<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionArticulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id', 'nombre', 'presentacion', 'condicion'
    ];


    public function DonacionCategoria()
    {
        return $this->belongsTo(DonacionCategoria::class);
    }
    
 
}
