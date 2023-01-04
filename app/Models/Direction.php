<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;

    protected $connection = 'mamore';
    protected $table = 'direcciones';
    protected $fillable = ['nombre'];

    public function solicitudCompra()
    {
        return $this->hasMany(SolicitudCompra::class);
    }
}
