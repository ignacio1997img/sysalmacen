<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionIngreso extends Model
{
    use HasFactory;

    protected $fillable = [
    'centro_id', 'tipodonante', 'donante_id', 'registeruser_id', 'nrosolicitud', 'fechadonacion', 'fechaingreso', 'gestion',
    'condicion', 'deleteuser_id', 'observacion'
    ];



}
