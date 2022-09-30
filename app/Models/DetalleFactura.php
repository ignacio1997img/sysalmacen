<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;
    protected $fillable = [
        'factura_id','registeruser_id', 'article_id', 'cantsolicitada', 'precio', 'totalbs',
        'cantrestante', 'fechaingreso', 'gestion','histcantsolicitada', 'histprecio', 'histtotalbs',
        'histcantrestante', 'histfechaingreso', 'histgestion', 'hist', 'condicion', 'deleteuser_id', 'sucursal_id'
      ];
}
