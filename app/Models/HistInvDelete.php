<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistInvDelete extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'inventario_id',
        'gestion',
        'start',
        'startUser_id',
        'finish',
        'finishUser_id',
        'observation',
        'observation1',
        'deleteObservation',
        'deleted_at',
        'deleteUser_id'
    ];

}
