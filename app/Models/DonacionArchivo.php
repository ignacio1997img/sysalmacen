<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionArchivo extends Model
{
    use HasFactory;
    protected $fillable =['entrada', 'nombre_origen', 'ruta', 'donacioningreso_id', 'user_id'];
}
