<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class DonacionIngreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
    'centro_id', 'tipodonante', 'onuempresa_id','persona_id', 'registeruser_id', 'nrosolicitud', 'fechadonacion', 'fechaingreso', 'gestion',
    'condicion','stock', 'deleteuser_id', 'observacion'
    ];

    public function IngresoDetalle()
    {
        return $this->hasMany(DonacionIngresoDetalle::class);
    }




}
