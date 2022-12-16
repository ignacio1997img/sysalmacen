<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use Illuminate\Http\Request;
use App\Models\InventarioAlmacen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InventarioAlmacenController extends Controller
{
    public function index()
    {
        $data = InventarioAlmacen::where('deleted_at', null)->get();
        $ok = InventarioAlmacen::where('deleted_at', null)->where('status', 1)->get();

        $max = InventarioAlmacen::where('deleted_at', null)->where('status', 0)->max('gestion');
        // return $max;
        // return 1;
        return view('almacenes.inventario.browse', compact('data', 'ok', 'max'));
    }

    public function finish(Request $request)
    {
        // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('hist',1)->select(DB::raw('SUM(cantrestante)'))->get();
        // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('hist',0)->select(DB::raw('SUM(cantrestante)'))->get();

        // return $detalle;
        
        DB::beginTransaction();
        try {
            $date = date('Y-m-d');

            $ok = InventarioAlmacen::where('id', $request->id)->first();

            // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('sucursal_id',1)->select(DB::raw('SUM(cantrestante)'))->get();
            $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->get();
            
            foreach($detalle as $item)
            {
                DetalleFactura::create([
                    'factura_id'            => $item->factura_id,
                    'registeruser_id'       => $item->registeruser_id,
                    'article_id'            => $item->article_id,
                    'cantsolicitada'        => $item->cantsolicitada,
                    'precio'                => $item->precio,
                    'totalbs'               => $item->totalbs,
                    'cantrestante'          => $item->cantrestante,
                    'fechaingreso'          => $item->fechaingreso,
                    'gestion'               => ($ok->gestion)+1,
                    'sucursal_id'           => $item->sucursal_id,

                    'histgestion'           => $item->gestion,
                    'hist'                  => 1,
                    'parent_id'             => $item->id
                ]);
            }

            // return count($detalle);
            
            $ok->update(['finish'=>$date, 'status'=>0, 'observation1'=>$request->observation1, 'finishUser_id' => Auth::user()->id]);
            DB::commit();
            return redirect()->route('inventory.index')->with(['message' => 'Gestion Cerrada Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('inventory.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }

    public function start(Request $request)
    {
        DB::beginTransaction();
        try {

            $date = date('Y-m-d');

            InventarioAlmacen::create([
                'gestion' => $request->gestion,
                'start'   => $date,
                'observation'=>$request->observation,
                'startUser_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('inventory.index')->with(['message' => 'Gestion creada Exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('inventory.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }
}
