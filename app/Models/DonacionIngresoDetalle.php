<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DonacionIngresoDetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable = [
        'donacioningreso_id', 'donacionarticulo_id', 'registeruser_id','estado','caracteristica', 'cantidad', 'precio', 'cantrestante', 'caducidad','condicion', 'deleteuser_id'      
    ];
    
    public function Ingreso()
    {
        return $this->belongsTo(DonacionIngreso::class, 'donacioningreso_id');
    }

    public function Articulo()
    {
        return $this->belongsTo(DonacionArticulo::class, 'donacionarticulo_id');
    }

    

}
