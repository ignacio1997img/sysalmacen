<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionEgreso extends Model
{
    use HasFactory;
    protected $fillable =[
        'centro_id', 'registeruser_id', 'nrosolicitud', 'fechaentrega', 'observacion', 'gestion', 'condicion', 'deleteuser_id'
    ];


}
