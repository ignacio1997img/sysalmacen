<?php

namespace App\Http\Controllers;

use App\Models\InventarioAlmacen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDirecciones() {
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

    public function getUnidades($id)
    {
        return DB::connection('mamore')->table('unidades as u')
            ->where('u.estado', 1)
            ->where('u.deleted_at', null)
            ->where('u.direccion_id', $id)
            ->select('u.id', 'u.nombre', 'u.sigla')
            ->orderBy('u.nombre','asc')
            ->get();
    }



    public function getDireccion($id) {
        try {
            return DB::connection('mamore')->table('direcciones as d')
                        ->where('d.id', $id)
                        ->select('*')
                        ->first();
        } catch (\Throwable $th) {
            return 'ERROR';
        }
    }

    public function getUnidad($id)
    {
        return DB::connection('mamore')->table('unidades as u')
            ->where('u.id', $id)
            ->select('*')
            ->first();
    }


    // para obtener las gestuiones de cada almacen que se encuentra registrado en inventario
    public function getGestione($id)
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('almacen_admin'))
        {
            return InventarioAlmacen::where('sucursal_id', $id)->where('deleted_at', null)->where('status', 0)->get();
        }
        else
        {
            return InventarioAlmacen::where('sucursal_id', $id)->where('deleted_at', null)->get();
        }
    }



}
