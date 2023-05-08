<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $connection = 'mamore';
    protected $table = 'unidades';
    protected $fillable = ['nombre', 'direccion_id', 'estado'];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direccion_id');
    }

}
