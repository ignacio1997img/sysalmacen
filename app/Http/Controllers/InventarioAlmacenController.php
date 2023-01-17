<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\HistInvDelete;
use Illuminate\Http\Request;
use App\Models\InventarioAlmacen;
use App\Models\SolicitudCompra;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class InventarioAlmacenController extends Controller
{
    public function index($id)
    {
        // return $id;
        $sucursal = Sucursal::where('id', $id)->first();
        $data = InventarioAlmacen::where('sucursal_id', $id)->where('deleted_at', null)->get();
        $ok = InventarioAlmacen::where('sucursal_id', $id)->where('deleted_at', null)->where('status', 1)->get();//si existe no se puede abrir nueva gestion

        $max = InventarioAlmacen::where('sucursal_id', $id)->where('deleted_at', null)->where('status', 0)->max('gestion');
        // return $date = date('Y');

        if(!$max)
        {
            $max = date('Y')-1;
        }

        // return $max;
        // return 1;
        return view('almacenes.sucursal.inventario.browse', compact('data', 'ok', 'max', 'sucursal'));
    }

    public function finish(Request $request)
    {
        // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('hist',1)->select(DB::raw('SUM(cantrestante)'))->get();
        // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('hist',0)->select(DB::raw('SUM(cantrestante)'))->get();

        // return $request;
        
        DB::beginTransaction();
        try {
            $date = date('Y-m-d');

            $ok = InventarioAlmacen::where('id', $request->id)->first();

            $sucursal_id = $request->sucursal_id;
            // $detalle = DetalleFactura::where('deleted_at', null)->where('cantrestante','!=', 0)->where('sucursal_id',1)->select(DB::raw('SUM(cantrestante)'))->get();
            $detalle = DetalleFactura::with(['factura.solicitud', 'factura'])
                ->where(function($query) use($sucursal_id){
                        $query->whereHas('factura.solicitud', function($query)use($sucursal_id) {
                            $query->where('sucursal_id', $sucursal_id)
                            ->where('deleted_at', null);
                        })
                        ->whereHas('factura', function($query) {
                            $query->where('deleted_at', null);
                        });
                })
                ->where('deleted_at', null)->where('cantrestante','!=', 0)->where('hist', 0)
                ->orderBy('fechaingreso', 'ASC')
                ->get();
            
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
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Gestion Cerrada Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }

    public function start(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {

            $date = date('Y-m-d');

            $aux = InventarioAlmacen::create([
                'sucursal_id' => $request->sucursal_id,
                'gestion' => $request->gestion,
                'start'   => $date,
                'observation'=>$request->observation,
                'startUser_id' => Auth::user()->id
            ]);
            // return $aux;
            SolicitudCompra::where('sucursal_id', $request->sucursal_id)->update(['inventarioAlmacen_id'=>$aux->id]);
            DB::commit();
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Gestion creada Exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function reabrir(Request $request)
    {
        // return $request;    
        DB::beginTransaction();
        try {
            $inv = InventarioAlmacen::where('id', $request->id)->first();
            $inv->update(['status'=> 1]);

            // return $inv;

            $aux = HistInvDelete::create([
                'inventario_id'=>$inv->id,
                'gestion'=>$inv->gestion,
                'start'=>$inv->start,
                'startUser_id'=>$inv->startUser_id,
                'finish'=>$inv->finish,
                'finishUser_id'=>$inv->finishUser_id,
                'observation'=>$inv->observation,
                'observation1'=>$inv->observation1,
                'deleteObservation'=>$request->observation1,
                'registeruser_id' => Auth::user()->id
            ]);
            $file = $request->file('archivo');

            if ($file) {
                    
                    $nombre_origen = $file->getClientOriginalName();
                    
                    $newFileName = Str::random(20).time().'.'.$file->getClientOriginalExtension();
                    
                    $dir = "InventarioAlmacen/HistInvDelete/".date('F').date('Y');
                    
                    Storage::makeDirectory($dir);
                    Storage::disk('public')->put($dir.'/'.$newFileName, file_get_contents($file));

                    $aux->update([
                        'nameFile'         => $nombre_origen,
                        'routeFile'                  => $dir.'/'.$newFileName,
                    ]);
            }

            // return $aux;
            $gestion = $inv->gestion;

            $data = SolicitudCompra::with(['factura'=>function($q)
                    {
                        $q->where('deleted_at', null);
                    },
                    'factura.detalle' => function($q) use($gestion){
                        $q->where('deleted_at', null)
                        ->where('hist', 1)
                        ->where('gestion', $gestion+1);
                    }])
                    ->where('deleted_at', null)
                    ->where('sucursal_id', $request->sucursal_id)->get();
            

            // return $data;    
            foreach($data as $sol)
            {
                foreach($sol->factura as $fa)
                {
                    foreach($fa->detalle as $item)
                    {
                        // return $item;
                        DetalleFactura::where('id', $item->id)
                        ->update(['deleted_at'=> Carbon::now(), 'deleteuser_id'=>Auth::user()->id, 'deleteObservation'=>$request->observation1, 'HistInvDelete_id'=>$aux->id]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Gestion Reabierta Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 110011;
            return redirect()->route('inventory.index', ['id'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }



    // para ver el historial de cada rearpetura de cada gestion
    public function indexHistInvDelete($sucursal, $gestion)
    {
        // return $gestion;
        $sucursal_id = $sucursal;
        $hist = HistInvDelete::with(['histDetalleFactura', 'user'])
            ->where('inventario_id', $gestion)->get();

        // return $hist;
        // return $hist->histDetalleFactura->sum('cantrestante');

        return view('almacenes.sucursal.inventario.histInvDelete.browse', compact('hist', 'sucursal_id'));
    }
}
