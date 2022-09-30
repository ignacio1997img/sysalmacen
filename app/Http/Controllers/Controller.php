<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDireccion() {
        try {
            return DB::connection('mamore')->table('direcciones as d')
                        ->where('deleted_at', null)
                        ->select('*')
                        ->orderBy('nombre','asc')
                        ->get();
        } catch (\Throwable $th) {
            return 'ERROR';
        }
    }

    public function direccionSucursal($id)
    {
        return DB::connection('mamore')->table('direcciones as d')
            ->join('sysalmacen.sucursal_direccions as sd', 'sd.direccionAdministrativa_id', 'd.id')
            ->where('sd.sucursal_id', $id)
            ->where('sd.status', 1)
            ->where('sd.deleted_at', null)
            ->select('d.id', 'd.nombre', 'd.sigla')
            ->orderBy('d.nombre','asc')
            ->get();

    }
}
