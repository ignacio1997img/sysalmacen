<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DonacionCategoria extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'condicion'
    ];

    public function DonacionArticulo()
    {
        return $this->hasMany(DonacionArticulo::class);
    }
}
