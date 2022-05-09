<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DonacionArticulo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'categoria_id', 'nombre', 'presentacion', 'condicion'
    ];

    public function DonacionCategoria()
    {
        return $this->belongsTo(DonacionCategoria::class);
    }
    
    public function IngresoDetalle()
    {
        return $this->hasMany(DonacionIngresoDetalle::class);
    }
 
}
