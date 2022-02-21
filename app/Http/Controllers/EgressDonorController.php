<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonacionArticulo;
use App\Models\DonacionCategoria;
use App\Models\DonacionIngreso;
use App\Models\DonacionIngresoDetalle;
use App\Models\DonadorEmpresa;
use App\Models\DonadorPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Centro;
use App\Models\CentroCategoria;
use App\Models\DonacionEgreso;
use App\Models\DonacionEgresoDetalle;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Auth;

class EgressDonorController extends Controller
{
   
    public function index()
    {
        $egreso = DB::table('donacion_egresos as d')
                ->join('centros as c', 'c.id', 'd.centro_id')
                ->select('d.id', 'd.nrosolicitud', 'd.observacion', 'c.nombre', 'd.fechaentrega', 'd.condicion')
                ->where('d.deleted_at', null)
                ->get();
                
return $egreso;
        return view('egressdonor.browse', compact('egreso'));
    }

    public function create()
    {        
        // return DB::table('donacion_ingreso_detalles as c')
        // ->join('donacion_articulos as a', 'a.id', 'c.donacionarticulo_id')
        // ->select('c.id', 'c.cantrestante', 'a.nombre')
        // ->where('c.donacioningreso_id', 11)
        // ->where('c.condicion', 1)
        // ->where('c.deleted_at', null)
        // ->get();
        $categoria = DonacionCategoria::where('deleted_at', null)
                ->where('condicion', 1)->get();
        $centrotipo = CentroCategoria::where('deleted_at', null)
        ->where('condicion', 1)->get();

        $vigente = DB::table('donacion_ingresos as di')
                ->join('donacion_ingreso_detalles as did', 'did.donacioningreso_id', 'di.id')
                ->join('centros as c', 'c.id', 'di.centro_id')
                ->select('di.nrosolicitud', 'c.nombre', 'di.id')
                ->where('did.deleted_at', null)
                ->where('di.deleted_at', null)
                ->where('did.cantrestante', '!=', 0)
                ->groupBy('di.nrosolicitud', 'c.nombre', 'di.id')
                ->get();

        return view('egressdonor.add', compact('categoria', 'centrotipo','vigente'));        
    }


    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            
            $user = Auth::user();
            $gestion = Carbon::parse($request->fechaingreso)->format('Y');

            $aux = DonacionEgreso::where('centro_id', $request->centro_id)
                ->where('deleted_at', null)
                ->get();
                
            $centro = Centro::find($request->centro_id);

                $length = 4;
                $char = 0;
                $type = 'd';
                $format = "%{$char}{$length}{$type}"; // or "$010d";     



            $request->merge(['nrosolicitud' => strtoupper($centro->sigla).'-'.sprintf($format, count($aux)+1)]);   
            
            $egreso = DonacionEgreso::create(
                [
                    'centro_id'         => $request->centro_id,
                    'registeruser_id'   => $user->id,
                    'nrosolicitud'      => $request->nrosolicitud,
                    'fechaentrega'      => $request->fechaentrega,
                    'observacion'       => $request->observacion,
                    'gestion'           => $gestion
                ]);

            $i=0;
            while($i< count($request->articulo_id))
            {
                DonacionEgresoDetalle::create([
                    'donacionegreso_id'             => $egreso->id,
                    'donacioningresodetalle_id'     => $request->articulo_id[$i], // id del detalle de donacion ingreso detalle
                    'registeruser_id'               => $user->id,
                    'cantentregada'                 => $request->cantidad[$i]

                ]);

                DonacionIngresoDetalle::where('id',$request->articulo_id[$i])->decrement('cantrestante', $request->cantidad[$i]);

                $d = DonacionIngresoDetalle::find($request->articulo_id[$i]);
                if($d->cantrestante == 0)
                {
                    DonacionIngresoDetalle::where('id',$request->articulo_id[$i])->update(['condicion'=>0]);
                }

                $detalle = DonacionIngresoDetalle::find($request->articulo_id[$i]);
                DonacionIngreso::where('id',$detalle->donacioningreso_id)->update(['condicion' => 0]);

                $i++;
            }
            

            DB::commit();
            return redirect()->route('egressdonor.index')->with(['message' => 'Egreso Registrado Exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('egressdonor.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);

        }
    }

    public function show($id)
    {
        // return $id;
        $egreso = DonacionEgreso::find($id);
        $centro = Centro::find($egreso->centro_id);
        $detalle = DB::table('donacion_egreso_detalles as ded')
                    ->join('donacion_ingreso_detalles as d', 'd.id', 'ded.donacioningresodetalle_id')
                    ->join('donacion_articulos as a', 'a.id', 'd.donacionarticulo_id')
                    ->select('a.nombre', 'a.presentacion', 'ded.cantentregada')
                    ->where('ded.donacionegreso_id', $id)
                    ->get();


        // return $detalle;

        return view('egressdonor.report', compact('egreso', 'centro', 'detalle'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

 
    public function destroy($id)
    {
        //
    }


    public function ajax_article($id)
    {
        return DB::table('donacion_ingreso_detalles as c')
        ->join('donacion_articulos as a', 'a.id', 'c.donacionarticulo_id')
        ->select('c.id', 'c.cantrestante', 'a.nombre')
        ->where('c.donacioningreso_id', $id)
        ->where('c.condicion', 1)
        ->where('c.deleted_at', null)
        ->get();
    }

    public function ajax_autollenar_articulo($id)
    {
        return DB::table('donacion_ingreso_detalles')
        ->select('cantrestante')
        ->where('id', $id)
        ->get();
    }
}
