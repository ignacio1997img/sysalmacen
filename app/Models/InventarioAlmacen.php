<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'gestion', 'start', 'startUser_id', 'finish', 'finishUser_id', 'observation', 'observation1', 'status'
    ];





}
