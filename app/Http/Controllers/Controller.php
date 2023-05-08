<?php

namespace App\Http\Controllers;

use App\Models\InventarioAlmacen;
use App\Models\SucursalDireccion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PeopleExt;

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

    //Para obtener todas la unidades de una direccion en especifica
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



    // PARA OBTENER LA INFORMACION DE CADA PERSONA CON SU CARGO
    //obtencioin de los funcionarios en la BD mamore
    public function getPeople($id)
    {        
        $funcionario = 'null';
        $funcionario = DB::connection('mamore')->table('people as p')
            ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            // ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            ->leftJoin('direcciones as d', 'd.id', 'c.direccion_administrativa_id')
            ->leftJoin('unidades as u', 'u.id', 'c.unidad_administrativa_id')
            ->leftJoin('jobs as j', 'j.id', 'c.job_id')
            ->where('c.status', 'firmado')
            ->where('c.deleted_at', null)
            ->where('p.id', $id)
            ->where('p.deleted_at', null)
            ->select('p.id as id_funcionario', 'p.ci as N_Carnet', 'c.cargo_id', 'c.job_id', 'j.name as cargo',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre"), 'p.first_name', 'p.last_name', 'c.direccion_administrativa_id as id_direccion', 'd.nombre as direccion',
                    'c.unidad_administrativa_id as id_unidad', 'u.nombre as unidad')
            ->first();

        if($funcionario && $funcionario->cargo_id != NULL)
        {
            $cargo = DB::connection('mysqlgobe')->table('cargo')
                ->where('id',$funcionario->cargo_id)
                ->select('*')
                ->first();
    
            $funcionario->cargo=$cargo->Descripcion;
        }
        if(!$funcionario)
        {
            return "Error";
        }
        return $funcionario;
    }


    public function getWorker($id)
    {        
        $funcionario = 'null';
        $funcionario = DB::connection('mamore')->table('people as p')
            ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            // ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            ->leftJoin('direcciones as d', 'd.id', 'c.direccion_administrativa_id')
            ->leftJoin('unidades as u', 'u.id', 'c.unidad_administrativa_id')
            ->leftJoin('jobs as j', 'j.id', 'c.job_id')
            ->where('c.status', 'firmado')
            ->where('c.deleted_at', null)
            ->where('p.id', $id)
            ->where('p.deleted_at', null)
            ->select('p.id as people_id', 'p.ci as ci', 'c.cargo_id', 'c.job_id', 'j.name as cargo',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre"), 'p.first_name', 'p.last_name', 'c.direccion_administrativa_id as id_direccion', 'd.nombre as direccion',
                    'c.unidad_administrativa_id as id_unidad', 'u.nombre as unidad')
            ->first();

        if($funcionario)
        {
            if($funcionario->cargo_id != NULL)
            {
                $cargo = DB::connection('mysqlgobe')->table('cargo')
                    ->where('id',$funcionario->cargo_id)
                    ->select('*')
                    ->first();
            
                $funcionario->cargo=$cargo->Descripcion;
            }
        }
        
        if(!$funcionario)
        {
            // $funcionario = PeopleExt::where('people_id', $id)
            //     ->where('status',1)
            //     ->where('deleted_at',null)
            //     ->select('cargo', 'people_id as people_id')
            //     ->first();          

            $funcionario = DB::connection('mamore')->table('people as p')
                ->join('sysalmacen.people_exts as px', 'px.people_id', 'p.id')
                ->where('px.status',1)
                ->where('px.deleted_at',null)
                ->where('px.people_id', $id)
                ->select('p.id as people_id', 'p.ci as ci', 'px.cargo', DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre"), 'p.first_name', 'p.last_name')
                ->first();
        }

        if(!$funcionario)
        {
            return NULL;
        }
        return $funcionario;
    }


    //Para obtener las direciones de cada almacen asignada
    public function getDireccionSucursal($id)
    {
        return SucursalDireccion::with(['direction'])
            ->where('sucursal_id', $id)
            ->where('status', 1)
            ->where('deleted_at', null)->get();
    }



}
