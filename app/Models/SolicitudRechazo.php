<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudRechazo extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicituderivada_id', 'observacion'
    ];
            
}
