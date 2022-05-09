<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DonacionEgreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable =[
        'centro_id', 'registeruser_id', 'nrosolicitud', 'fechaentrega', 'observacion', 'gestion', 'condicion', 'deleteuser_id'
    ];


}
