<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Solicitud;
use App\Models\SolicitudDerivada;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\SolicitudDetalle;
use App\Models\SolicitudRechazo;

class BandejaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();


        $pendientes = DB::table('solicituds as s')
                ->join('solicitud_derivadas as sd', 'sd.solicitud_id', 's.id')
                ->select('sd.id','s.id as solicitud_id','sd.de_nombre', 's.nroproceso', 'sd.created_at', 's.unidadadministrativa')  
                // ->Orwhere('sd.de_id', $user->id)
                ->where('sd.dirigido_id', $user->id)              
                ->where('sd.deleted_at', null)
                ->where('s.deleted_at', null)
                ->where('sd.rechazado', 0)
                ->where('sd.aprobado', 0)                
                ->get();

        $rechazado = DB::table('solicituds as s')
                ->join('solicitud_derivadas as sd', 'sd.solicitud_id', 's.id')
                ->select('sd.id','s.id as solicitud_id','sd.de_nombre', 's.nroproceso', 'sd.created_at', 's.unidadadministrativa')
                // ->where('sd.dirigido_id', $user->id)
                ->where('sd.deleted_at', null)
                ->where('sd.rechazado', 1)
                // ->where('sd.de_id', $user->id)
                ->where('sd.dirigido_id', $user->id)
                ->get();


        $aprobado = DB::table('solicituds as s')
                ->join('solicitud_derivadas as sd', 'sd.solicitud_id', 's.id')
                ->select('sd.id','s.id as solicitud_id','sd.de_nombre', 's.nroproceso', 'sd.created_at', 's.unidadadministrativa', 'sd.atendido')
                // ->where('sd.dirigido_id', $user->id)
                ->where('sd.deleted_at', null)
                ->where('s.deleted_at', null)
                ->where('sd.aprobado', 1)
                // ->where('sd.de_id', $user->id)
                ->where('sd.dirigido_id', $user->id)
                ->get();
        // return $rechazado;

        $cont=0;
        while($cont < count($pendientes))
        {
            $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $pendientes[$cont]->unidadadministrativa)
                ->get();

            $pendientes[$cont]->unidad = $unidad[0]->Nombre;
            $cont++;
        }

        $re=0;

        while($re < count($rechazado))
        {
            $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $rechazado[$re]->unidadadministrativa)
                ->get();

            $rechazado[$re]->unidad = $unidad[0]->Nombre;
            $re++;
        }

        $apr=0;

        while($apr < count($aprobado))
        {
            $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $aprobado[$apr]->unidadadministrativa)
                ->get();

            $aprobado[$apr]->unidad = $unidad[0]->Nombre;
            $apr++;
        }


        return view('solicitud.bandeja.browse', compact('pendientes', 'rechazado', 'aprobado'));
    }

    public function view($id)
    {
        $solicitud =  Solicitud::find($id);

        $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('id', $solicitud->unidadadministrativa)
                ->get();
        // return $unidad;

        $detalle = DB::table('solicitud_detalles as sd')
                ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')
                ->select('p.codigo', 'a.nombre', 'a.presentacion', 'sd.cantidad')
                ->where('sd.solicitud_id', $solicitud->id)
                ->get();

        $derivacion = SolicitudDerivada::where('solicitud_id', $solicitud->id)->get();
        // return $derivacion;


        return view('solicitud.bandeja.viewpendiente', compact('solicitud', 'unidad', 'detalle', 'derivacion'));
    }

    public function view_aprobada($id)
    {
        $solicitud =  Solicitud::find($id);

        $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('id', $solicitud->unidadadministrativa)
                ->get();
        // return $unidad;

        $detalle = DB::table('solicitud_detalles as sd')
                ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')
                ->select('p.codigo', 'a.nombre', 'a.presentacion', 'sd.cantidad','sd.cantidadentregar')
                ->where('sd.solicitud_id', $solicitud->id)
                ->get();

        $derivacion = SolicitudDerivada::where('solicitud_id', $solicitud->id)->get();
        // return $derivacion;


        return view('solicitud.bandeja.viewaprobada', compact('solicitud', 'unidad', 'detalle', 'derivacion'));
    }

    public function view_editar($id)
    {
        $solicitud =  Solicitud::find($id);
        $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('id', $solicitud->unidadadministrativa)
                ->get();

        $detalle = DB::table('solicitud_detalles as sd')
                ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')
                ->select('p.codigo', 'p.nombre as partida','a.id as articulo_id', 'a.nombre as articulo', 'a.presentacion', 'sd.cantidad', 'sd.id as detalle_id')
                ->where('sd.solicitud_id', $solicitud->id)
                ->get();
        // return $detalle;

        $derivacion = SolicitudDerivada::where('solicitud_id', $solicitud->id)->get();

        return view('solicitud.bandeja.editaraprobar', compact('solicitud', 'unidad', 'detalle', 'derivacion'));
    }

    public function editar_aprobar(Request $request)
    {
        DB::beginTransaction();
        // return $request;
        try {

            $solicitud = Solicitud::find($request->solicitud_id);
            // return $solicitud;
            Solicitud::where('id', $request->solicitud_id)->update(['estado'=> 'Aprobado']);
            SolicitudDerivada::where('solicitud_id', $solicitud->id)->update(['aprobado' => 1, 'fechapr'=>Carbon::now()]);

            $i=0;

            while($i<count($request->detalle_id))
            {
                SolicitudDetalle::where('id',$request->detalle_id[$i])->update(['cantidadentregar' => $request->cantidadentregar[$i]]);
                $i++;
            }
            // return 1;

            SolicitudDetalle::where('solicitud_id',$request->solicitud_id)->where('cantidadentregar', 0)->update(['condicion' => 0]);


            // return $request;
            DB::commit();
            return redirect()->route('bandeja.index')->with(['message' => 'Solicitud Aprobada Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('bandeja.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


    public function rechazo(Request $request)
    {
        DB::beginTransaction();
        
        // return $request;

        try {
        // return 1;

            SolicitudRechazo::create([
                'solicituderivada_id' => $request->derivacion_id,
                'observacion'         => $request->observacion
            ]);
        // return $request;

            SolicitudDerivada::where('id', $request->derivacion_id)->update(['rechazado' => 1, 'fechapr'=>Carbon::now()]);
            $soldev = SolicitudDerivada::find($request->derivacion_id);

            Solicitud::where('id', $soldev->solicitud_id)->update(['estado'=> 'Rechazado', 'condicion'=>0]);

            DB::commit();
            return redirect()->route('bandeja.index')->with(['message' => 'Solicitud Rechazada.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('bandeja.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function aprobar(Request $request)
    {
        DB::beginTransaction();

        try {
            SolicitudDerivada::where('id', $request->derivacion_id)->update(['aprobado' => 1, 'fechapr'=>Carbon::now()]);
            $soldev = SolicitudDerivada::find($request->derivacion_id);

            Solicitud::where('id', $soldev->solicitud_id)->update(['estado'=> 'Aprobado']);

            // return count(SolicitudDetalle::where('solicitud_id',$soldev->solicitud_id)->get());

            $detalles=SolicitudDetalle::where('solicitud_id',$soldev->solicitud_id)->get();
            // return $detalles;
            foreach($detalles as $item)
            {
                SolicitudDetalle::where('id',$item->id)->update(['cantidadentregar' => $item->cantidad]);
            }

            DB::commit();
            return redirect()->route('bandeja.index')->with(['message' => 'Solicitud Aprobada Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('bandeja.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
