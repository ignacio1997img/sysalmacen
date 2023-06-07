<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalSubAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id',
        'name',
        'status',
        'deleted_at'
    ];
}
