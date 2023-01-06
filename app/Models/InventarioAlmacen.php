<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'gestion', 'start', 'startUser_id', 'finish', 'finishUser_id',
        'observation', 'observation1', 'status', 'sucursal_id'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class. 'sucursal_id');
    }
    public function userStart()
    {
        return $this->belongsTo(User::class, 'startUser_id');
    }

    //para finalizar
    public function userFinish()
    {
        return $this->belongsTo(User::class, 'finishUser_id');
    }






}
