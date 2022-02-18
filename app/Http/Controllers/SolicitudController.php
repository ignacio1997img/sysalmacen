<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Solicitud;
use App\Models\SolicitudDetalle;
use App\Models\SolicitudDerivada;
use App\Models\Article;
use App\Models\SolicitudCompra;
use App\Models\Sucursal;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Brian2694\Toastr\Toastr as ToastrToastr;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
               
        $user = Auth::user();
        $solicitud = DB::table('solicituds as s')
                // ->join('solicitud_detalles as sd', 'sd.solicitud_id', 's.id')
                ->select('s.id', 's.nroproceso', 's.fechasolicitud', 's.derivado','s.estado')
                ->where('s.deleted_at', null)
                ->where('s.registeruser_id', $user->id)
                ->get();
        
        $usuarios = User::where('id', '!=', $user->id)
                ->where('id', '!=', 1)->get();

        $cant = count(DB::table('solicituds as s')
                ->select('s.id', 's.nroproceso', 's.fechasolicitud', 's.derivado','s.estado')
                ->where('s.deleted_at', null)
                ->where('s.registeruser_id', $user->id)
                ->where('estado', '!=', 'Entregado')
                ->where('estado', '!=', 'Rechazado')
                ->get());
            
            // return $cant;

        $cont = 0;
        while($cont < count($usuarios))
        {
            $funcionarios = DB::connection('mysqlgobe')->table('contribuyente as co')                            
            ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
            ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
            ->join('cargo as ca', 'ca.ID', 'c.idCargo')
            ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
            ->where('c.Estado', 1)
            ->where('c.ID',$usuarios[$cont]->funcionario_id)
            ->get();

            $usuarios[$cont]->funcionario = $funcionarios[0]->nombrecontribuyente;
            $usuarios[$cont]->cargo =$funcionarios[0]->cargo;
            $usuarios[$cont]->unidad =$funcionarios[0]->unidad;

            $cont++;
        }

        // return $usuarios;
        return view('solicitud.browse', compact('solicitud', 'usuarios', 'cant'));
    }
 


    public function create()
    {

        $user = Auth::user();

        $unidad = DB::connection('mysqlgobe')->table('contratos as c')
                ->join('unidadadminstrativa AS u', 'u.ID', 'c.idDependencia')
                ->select('u.ID as id','u.Nombre as unidad','u.sigla')
                ->where('c.Estado', 1)
                ->where('c.ID',$user->funcionario_id)
                ->get();

        // return $unidad;
        
        $aux = DB::table('solicituds')
            ->select('*')
            ->where('estado', 'Entregado')
            ->where('unidadadministrativa', $unidad[0]->id)
            ->where('condicion', 1)
            ->where('deleted_at', null)
            ->get();
        

            $length = 4;
            $char = 0;
            $type = 'd';
            $format = "%{$char}{$length}{$type}"; // or "$010d";
            



        $nroproceso = strtoupper($unidad[0]->sigla).'-'.sprintf($format, count($aux)+1);



        $sucursal = Sucursal::all();

        return view('solicitud.add', compact('unidad', 'nroproceso'));
    }


    public function store(Request $request)
    {

        // return $request;
        DB::beginTransaction();
        try {
            

            $user = Auth::user();
            $gestion = Carbon::parse($request->fechasolicitud)->format('Y');
            $funcargos = DB::connection('mysqlgobe')->table('contribuyente as co')                            
                                            ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
                                            ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
                                            ->join('cargo as ca', 'ca.ID', 'c.idCargo')
                                            ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
                                            ->where('c.Estado', 1)
                                            ->where('c.ID', $user->funcionario_id)
                                            ->get();

            // en procesos de mantenimiento.............
            $sol = SolicitudCompra::find($request->solicitudcompra_id[0]);
            // return $sol;

            $solicitud = Solicitud::create([
                            'sucursal_id'               => $sol->sucursal_id,
                            'unidadadministrativa'      => $request->unidadadministrativa,
                            'registeruser_id'           => $user->id,
                            'cargo'                     => $funcargos[0]->nombrecontribuyente.' - '.$funcargos[0]->cargo,
                            'nroproceso'                => $request->nroproceso,
                            'fechasolicitud'            => $request->fechasolicitud,
                            'gestion'                   => $gestion
                        ]);

       

            $cont = 0;
       
            while($cont < count($request->detallefactura_id))
            {
                SolicitudDetalle::create([
                            'solicitud_id'          => $solicitud->id,
                            'solicitudcompra_id'    => $request->solicitudcompra_id[$cont],
                            'detallefactura_id'     => $request->detallefactura_id[$cont],
                            'cantidad'              => $request->cantidad[$cont]
                ]);
                $cont++;
            }

            DB::commit();
        } catch (\Throwable $th) {            
            DB::rollBack();
        }

        return redirect()->route('solicitud.index');
    }

    public function derivar_proceso(Request $request)
    {
        // return $request;
        
        DB::beginTransaction();
        $user = Auth::user();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente as co')                            
            ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
            ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
            ->join('cargo as ca', 'ca.ID', 'c.idCargo')
            ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
            ->where('c.Estado', 1)
            ->where('c.ID',$user->funcionario_id)
            ->get();

        // return $funcionarios;


        $userdirigido = User::find($request->dirigido);
        // return $userdirigido;
        $funcionariosdirigido = DB::connection('mysqlgobe')->table('contribuyente as co')                            
            ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
            ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
            ->join('cargo as ca', 'ca.ID', 'c.idCargo')
            ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
            ->where('c.Estado', 1)
            ->where('c.ID',$userdirigido->funcionario_id)
            ->get();

        // return 2;

        try {
            $sol = SolicitudDerivada::create([
                'solicitud_id'      => $request->id,
                'de_id'             => $user->id,
                'de_nombre'         =>  $funcionarios[0]->nombrecontribuyente.' - '.$funcionarios[0]->cargo,
                'dirigido_id'       => $request->dirigido,
                'dirigido_nombre'   => $funcionariosdirigido[0]->nombrecontribuyente.'- '.$funcionariosdirigido[0]->cargo,
                'detalles'          => $request->observacion
            ]);

            Solicitud::where('id',$request->id)->update(['estado'=>'Derivado', 'derivado'=>1]);
       
            DB::commit();
            return redirect()->route('solicitud.index')->with(['message' => 'Ha sido derivado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('solicitud.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
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


        return view('solicitud.solicitudegreso.view', compact('solicitud', 'unidad', 'detalle'));
    }



    public function destroy(Request $request)
    {       
        
        DB::beginTransaction();
        try{

            $user = Auth::user();

            $sol = Solicitud::find($request->id);
            Solicitud::where('id', $sol->id)->update(['deleteuser_id' => $user->id, 'deleted_at' => Carbon::now(), 'condicion' => 0]);
    
            $det = SolicitudDetalle::where('solicitud_id',$sol->id)->get();
            SolicitudDetalle::where('solicitud_id', $sol->id)->update(['deleteuser_id' => $user->id, 'deleted_at' => Carbon::now(), 'condicion' => 0]);

            SolicitudDerivada::where('solicitud_id', $sol->id)->update(['deleteuser_id' => $user->id,'deleted_at' => Carbon::now(), 'condicion' => 0]);

            DB::commit();
            return redirect()->route('solicitud.index')->with(['message' => 'Registro eliminado exitosamente.', 'alert-type' => 'success']);
        }
        catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('solicitud.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }






    public function ajax_modalidadcompra($id)
    {
        return DB::table('solicitud_compras as sp')
            ->join('modalities as m', 'm.id', 'sp.modality_id')
            ->join('facturas as f', 'f.solicitudcompra_id', 'sp.id')
            ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
            ->select('sp.id','m.nombre', 'sp.nrosolicitud')
            ->where('sp.unidadadministrativa', $id)
            ->where('f.condicion',1)
            // ->where('sp.condicion',1)
            ->where('sp.deleted_at', null)
            ->where('df.cantrestante', '!=', 0)
            ->groupBy('sp.id','m.nombre', 'sp.nrosolicitud')
            ->get();
    }

    public function ajax_articulo($id)
    {
        return DB::table('solicitud_compras as sc')
            ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
            ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
            ->join('articles as a', 'a.id', 'df.article_id')
            ->select('df.id', 'a.nombre')
            ->where('sc.id', $id)
            ->where('df.condicion', 1)
            ->where('df.deleted_at', null)
            ->get();
    }

    public function ajax_autollenar_articulo($id)
    {
        return DB::table('detalle_facturas as df')
            ->join('articles as a', 'a.id', 'df.article_id')
            ->select('a.nombre as articulo', 'df.cantrestante', 'a.presentacion', 'df.precio')
            ->where('df.id', $id)
            ->get();
    }
}
