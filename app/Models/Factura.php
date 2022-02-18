<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = ['solicitudcompra_id', 'provider_id', 'registeruser_id',
                            'tipofactura', 'fechafactura', 'montofactura', 'nrofactura',
                            'nroautorizacion', 'nrocontrol' ,'fechaingreso', 'gestion',
                            'condicion', 'deleteuser_id'
                        ];
}
