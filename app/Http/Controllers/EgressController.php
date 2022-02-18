<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Sucursal;
use App\Models\SolicitudEgreso;
use App\Models\Solicitud;
use App\Models\SolicitudDerivada;
use App\Models\DetalleEgreso;
use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\SolicitudCompra;
use App\Models\SolicitudDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\DonacionArticulo;

class EgressController extends Controller
{
    public function index()
    {
        // $user =Auth::user();
    
        // $activo = DB::table('users as u')
        //         ->join('sucursal_users as su', 'su.user_id', 'u.id')
        //         ->join('sucursals as s', 's.id', 'su.sucursal_id')
        //         ->select('u.id as user', 'u.name', 's.id', 's.nombre')
        //         ->where('u.id',$user->id)
        //         ->where('su.condicion',1)
        //         ->get();


        // $egreso = SolicitudEgreso::where('deleted_at', null)
        //         ->where('sucursal_id', $activo[0]->id)
        //         ->get();

       
        $user =Auth::user();
    
        $activo = DB::table('users as u')
                ->join('sucursal_users as su', 'su.user_id', 'u.id')
                ->join('sucursals as s', 's.id', 'su.sucursal_id')
                ->select('u.id as user', 'u.name', 's.id', 's.nombre')
                ->where('u.id',$user->id )
                ->where('su.condicion',1)
                ->get();
        // dd($activo);
        $realizado = DB::table('solicituds as s')
            ->join('solicitud_derivadas as sd','sd.solicitud_id','s.id')
            ->select('s.nroproceso', 's.fechasolicitud', 'sd.de_nombre as de', 'sd.dirigido_nombre as aprobado', 'sd.fechapr', 's.unidadadministrativa', 's.id as solicitud_id')
            ->where('sd.aprobado',1)
            ->where('s.estado',"Entregado")
            ->where('s.deleted_at', null)
            ->where('s.condicion',1)
            ->where('sd.condicion',1)
            ->where('sd.deleted_at', null)
            ->where('s.sucursal_id',$activo[0]->id)
            ->get();
        $j=0;
        while($j < count($realizado))
        {
            $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $realizado[$j]->unidadadministrativa)
                ->get();
            $realizado[$j]->unidad = $unidad[0]->Nombre;
            $j++;
        }

        $pendiente = DB::table('solicituds as s')
            ->join('solicitud_derivadas as sd','sd.solicitud_id','s.id')
            ->select('s.nroproceso', 's.fechasolicitud', 'sd.de_nombre as de', 'sd.dirigido_nombre as aprobado', 'sd.fechapr', 's.unidadadministrativa', 's.id as solicitud_id')
            ->where('sd.aprobado',1)
            ->where('s.estado',"Aprobado")
            ->where('s.deleted_at', null)
            ->where('s.condicion',1)
            ->where('sd.condicion',1)
            ->where('sd.deleted_at', null)
            ->where('s.sucursal_id',$activo[0]->id)
            ->get();

        $i=0;

        while($i < count($pendiente))
        {
            $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $pendiente[$i]->unidadadministrativa)
                ->get();
            $pendiente[$i]->unidad = $unidad[0]->Nombre;
            $i++;
        }


        return view('egress.browse', compact('pendiente', 'realizado'));
    }

    public function view_pendiente($id)
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


        return view('egress.viewegresopendiente', compact('solicitud', 'unidad', 'detalle', 'derivacion'));
        // return view('egress.viewegresopendiente');
    }

    public function store_egreso_pendiente(Request $request)
    {
        // return $request;

        DB::beginTransaction();
        try {

            $ok = SolicitudDetalle::where('solicitud_id', $request->solicitud_id)->get();
            
            $aux = true;
            foreach($ok as $data)
            {
                $def = DetalleFactura::find($data->detallefactura_id);
                
                if($data->cantidadentregar > $def->cantrestante)
                {
                    // return DetalleFactura::find($data->detallefactura_id);
                    $aux=false;
                    $df = DetalleFactura::find($data->detallefactura_id);
                    $articulo = Article::find($df->article_id);
                    // return $articulo;
                    return redirect()->route('egres.index')->with(['message' => 'La cantidad que intenta sacar es mayor a la cantidad de Stock de '. $articulo->nombre.' que tiene disponible el almacen.', 'alert-type' => 'error']);
                }
            }

            if($aux == true)
            {
                $user = Auth::user();
                $funcionarios = DB::connection('mysqlgobe')->table('contribuyente as co')                            
                    ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
                    ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
                    ->join('cargo as ca', 'ca.ID', 'c.idCargo')
                    ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
                    ->where('c.Estado', 1)
                    ->where('c.ID',$user->funcionario_id)
                    ->get();

                SolicitudDerivada::where('solicitud_id', $request->solicitud_id)->update(['atendido' => 1]);
                Solicitud::where('id', $request->solicitud_id)->update(['estado' => 'Entregado', 'atendidopor'=> $funcionarios[0]->nombrecontribuyente.' - '.$funcionarios[0]->cargo]);

                $detalle = SolicitudDetalle::where('solicitud_id', $request->solicitud_id)->get();
                
            
                foreach($detalle as $data)
                {
                    DetalleFactura::where('id',$data->detallefactura_id)->decrement('cantrestante', $data->cantidadentregar);

                    $d = DetalleFactura::find($data->detallefactura_id);
                    if($d->cantrestante == 0)
                    {
                        DetalleFactura::where('id',$data->detallefactura_id)->update(['condicion'=>0]);
                    }

                    $detalle = DetalleFactura::find($data->detallefactura_id);
                    $factura = Factura::find($detalle->factura_id);
                    SolicitudCompra::where('id',$factura->solicitudcompra_id)->update(['condicion' => 0]);

                }              


                DB::commit();
                return redirect()->route('egres.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
            }
            else
            {
                return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }








    // en mantenimineto los metodos

    public function create()
    {
        $user = Auth::user();
        $sucursales = Sucursal::join('sucursal_users as u','u.sucursal_id', 'sucursals.id')
                    ->select('sucursals.id','sucursals.nombre','u.condicion')
                    ->where('u.condicion',1)
                    ->where('u.user_id', $user->id)
                    ->get();

        $da = $this->getIdDireccionInfo(); 

        return view('egress.add', compact('sucursales', 'da'));
    }//anulado 

 
    public function store(Request $request)
    {
        // return $request;

        $user = Auth::user();
        $gestion = Carbon::parse($request->fechaingreso)->format('Y');
        DB::beginTransaction();
        try { 

            $egreso=SolicitudEgreso::create([
                        'sucursal_id'               => $request->sucursal_id,
                        'unidadadministrativa'      => $request->unidadadministrativa,
                        'registeruser_id'           => $user->id,
                        'nropedido'                 => $request->nropedido,
                        'fechasolicitud'            => $request->fechasolicitud,
                        'fechaegreso'               => $request->fechaegreso,
                        'gestion'                   => $gestion
                    ]);

            $cont = 0;
            
            // return $egreso;
            while($cont < count($request->detallefactura_id))
            {
                    DetalleEgreso::create([
                            'solicitudegreso_id'    => $egreso->id,
                            'registeruser_id'       => $user->id,
                            'detallefactura_id'     => $request->detallefactura_id[$cont],
                            'cantsolicitada'        => $request->cantidad[$cont],
                            'precio'                => $request->precio[$cont],
                            'totalbs'               => $request->totalbs[$cont],
                            'gestion'               => $gestion
                    ]);


                    DetalleFactura::where('id',$request->detallefactura_id[$cont])->decrement('cantrestante', $request->cantidad[$cont]);
                    $cont++;
            }
            
            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }//anulado por mantenimiento

    public function destroy(Request $request)
    {       
        
        DB::beginTransaction();
        try{

            $sol = SolicitudEgreso::find($request->id);
            SolicitudEgreso::where('id', $sol->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);
    
            DetalleEgreso::where('solicitudegreso_id', $sol->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);

            $detalle = DetalleEgreso::where('solicitudegreso_id', $sol->id)->get();

            // return $detalle;
            $i=0;

            while($i < count($detalle))
            {                
                DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->increment('cantrestante', $detalle[$i]->cantsolicitada);
                $i++;
            }





            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
            

    }//anulado por mantenimiento


    protected function view_egreso($id)
    {
        // return $id;
        $sol = Solicitud::find($id);

        // return $sol;

       
        $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                ->select('Nombre')
                ->where('ID', $sol->unidadadministrativa)
                ->get();

        $detalle = DB::table('solicituds as s')
                ->join('solicitud_detalles as sd', 'sd.solicitud_id', 's.id')
                ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('solicitud_compras as sc', 'sc.id', 'sd.solicitudcompra_id')
                ->join('modalities as m', 'm.id', 'sc.modality_id')
                ->select('m.nombre as modalidad', 'sc.nrosolicitud as numero', 'a.nombre as articulo', 'a.id as codigo', 'a.presentacion', 'sd.cantidadentregar','df.precio')
                ->where('s.id',  $sol->id)
                ->where('sd.deleted_at', null)
                ->get();


        
        return view('egress.report', compact('sol', 'unidad', 'detalle'));
    }



   

    protected function ajax_solicitud_compra($id)
    {
        $solicitud = DB::table('solicitud_compras as com')
        ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
        ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
        ->join('modalities as m', 'm.id', 'com.modality_id')
        ->select('com.id', 'm.nombre', 'com.nrosolicitud')
        ->where('fd.condicion', 1)
        ->where('f.condicion', 1)
        ->where('com.unidadadministrativa', $id)
        ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
        ->orderBy('com.fechaingreso')
        ->get();
        return $solicitud;
    }

    protected function ajax_egres_select_article($id)
    {
        $articulo = DB::table('solicitud_compras as com')
                    ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
                    ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
                    ->join('modalities as m', 'm.id', 'com.modality_id')
                    ->join('articles as a', 'a.id', 'fd.article_id')
                    ->select('fd.id', 'a.nombre')
                    ->where('fd.condicion', 1)
                    ->where('com.id', $id)
                    ->get();
        return $articulo;
        
    }


    protected function ajax_egres_select_article_detalle($id)
    {
        $detalle = DB::table('detalle_facturas as fd')
                    ->join('articles as a', 'a.id', 'fd.article_id')
                    ->select('fd.id', 'a.presentacion', 'fd.precio', 'fd.cantrestante')
                    ->where('fd.id', $id)
                    ->get();
        return $detalle;
    }

}


    
