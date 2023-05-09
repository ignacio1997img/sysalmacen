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
use App\Models\SucursalUser;
use App\Models\InventarioAlmacen;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use App\Models\SucursalUnidadPrincipal;

class EgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
       
        
    
        // $activo = DB::table('users as u')
        //         ->join('sucursal_users as su', 'su.user_id', 'u.id')
        //         ->join('sucursals as s', 's.id', 'su.sucursal_id')
        //         ->select('u.id as user', 'u.name', 's.id', 's.nombre')
        //         ->where('u.id',$user->id )
        //         ->where('su.condicion',1)
        //         ->get();
        // // dd($activo);
        // $realizado = DB::table('solicituds as s')
        //     ->join('solicitud_derivadas as sd','sd.solicitud_id','s.id')
        //     ->select('s.nroproceso', 's.fechasolicitud', 'sd.de_nombre as de', 'sd.dirigido_nombre as aprobado', 'sd.fechapr', 's.unidadadministrativa', 's.id as solicitud_id')
        //     ->where('sd.aprobado',1)
        //     ->where('s.estado',"Entregado")
        //     ->where('s.deleted_at', null)
        //     ->where('s.condicion',1)
        //     ->where('sd.condicion',1)
        //     ->where('sd.deleted_at', null)
        //     ->where('s.sucursal_id',$activo[0]->id)
        //     ->get();
        // $j=0;
        // while($j < count($realizado))
        // {
        //     $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
        //         ->select('Nombre')
        //         ->where('ID', $realizado[$j]->unidadadministrativa)
        //         ->get();
        //     $realizado[$j]->unidad = $unidad[0]->Nombre;
        //     $j++;
        // }

        // $pendiente = DB::table('solicituds as s')
        //     ->join('solicitud_derivadas as sd','sd.solicitud_id','s.id')
        //     ->select('s.nroproceso', 's.fechasolicitud', 'sd.de_nombre as de', 'sd.dirigido_nombre as aprobado', 'sd.fechapr', 's.unidadadministrativa', 's.id as solicitud_id')
        //     ->where('sd.aprobado',1)
        //     ->where('s.estado',"Aprobado")
        //     ->where('s.deleted_at', null)
        //     ->where('s.condicion',1)
        //     ->where('sd.condicion',1)
        //     ->where('sd.deleted_at', null)
        //     ->where('s.sucursal_id',$activo[0]->id)
        //     ->get();

        // $i=0;

        // while($i < count($pendiente))
        // {
        //     $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
        //         ->select('Nombre')
        //         ->where('ID', $pendiente[$i]->unidadadministrativa)
        //         ->get();
        //     $pendiente[$i]->unidad = $unidad[0]->Nombre;
        //     $i++;
        // }
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        
        if(!$sucursal)
        {
            return "Contactese con el administrador";
        }
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }
        else
        {
            $user =Auth::user();
            $activo = DB::table('users as u')
                    ->join('sucursal_users as su', 'su.user_id', 'u.id')
                    ->join('sucursals as s', 's.id', 'su.sucursal_id')
                    ->select('u.id as user', 'u.name', 's.id', 's.nombre')
                    ->where('u.id',$user->id )
                    ->where('su.condicion',1)
                    ->first();
    
            $query_filter = 'se.sucursal_id ='.$activo->id;
        }

        // $gestion = InventarioAlmacen::where('status', 1)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
        // return $gestion;

        $data = DB::table('sysalmacen.solicitud_egresos as se')
            ->join('sysadmin.unidades as u', 'u.id', 'se.unidadadministrativa')
            ->join('sysadmin.direcciones as d', 'd.id', 'u.direccion_id')
            ->join('sysalmacen.sucursals as s', 's.id', 'se.sucursal_id')
            ->select('se.inventarioAlmacen_id as inventario_id', 'se.gestion', 'se.id', 'se.nropedido', 'se.fechasolicitud', 'se.fechaegreso', 'se.created_at','u.nombre as unidad', 'd.nombre as direccion', 's.nombre as sucursal')
            ->where('se.deleted_at', null)
            ->whereRaw($query_filter)
            // ->orderBy('se.id', 'DESC')
            ->get();
       

        return view('almacenes.egress.browse', compact('data', 'gestion'));
    }


    public function list($type,$search = null){
        // return $type;
        $paginate = request('paginate') ?? 10;
        $user = Auth::user();

        $sucursal = SucursalUser::where('user_id', $user->id)->where('condicion', 1)->where('deleted_at', null)->first();
        
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada

        $query_filter = 'sucursal_id = '.$sucursal->sucursal_id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }

        
        
        if($type == 'egreso')
        {
            $data = SolicitudEgreso::with(['sucursal', 'unidad', 'direccion'])
            ->where('deleted_at', NULL)
            ->whereRaw($query_filter)
            ->where(function($query) use ($search){
                if($search){
                    $query->OrWhereRaw($search ? "gestion like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "nropedido like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "id like '%$search%'" : 1);
                }
            })
            ->whereRaw($search ? "(gestion like '%$search%' or nropedido like '%$search%' or id like '%$search%')" : 1)

            ->orderBy('id', 'DESC')->paginate($paginate);
            return view('almacenes.egress.listEgreso', compact('data', 'gestion'));

        }
        else
        {
            $data =  SolicitudPedido::with(['solicitudDetalle'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrWhereRaw($search ? "gestion like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "nropedido like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "id like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "unidad_name like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "direccion_name like '%$search%'" : 1);
                    }
                })
                ->where('deleted_at', NULL)
                ->whereRaw('(status = "Aprobado" or status = "Entregado")')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);

            return view('almacenes.egress.listSolicitud', compact('data', 'gestion'));
        }
        
      

    }


    // en mantenimineto los metodos

    public function create()
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }


        // return 1;
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();

        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada

        if(!$sucursal)
        {
            return "Contactese con el administrador";
        }

        $da = $this->direccionSucursal($sucursal->sucursal_id);
        $sucursal = Sucursal::where('id', $sucursal->sucursal_id)->first();


        return view('almacenes.egress.add', compact('sucursal', 'da', 'gestion'));
    }//anulado 

 
    public function store(Request $request)
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return $request;

        $user = Auth::user();
        // $gestion = Carbon::parse($request->fechaingreso)->format('Y');
        DB::beginTransaction();
        try { 

            $egreso=SolicitudEgreso::create([
                        'sucursal_id'               => $request->sucursal_id,
                        'unidadadministrativa'      => $request->unidadadministrativa,
                        'direccionadministrativa'   => $request->direccionadministrativa,
                        'registeruser_id'           => $user->id,
                        'nropedido'                 => $request->nropedido,
                        'fechasolicitud'            => $request->fechasolicitud,
                        'fechaegreso'               => $request->fechaegreso,
                        'gestion'                   => $request->gestion,
                        'inventarioAlmacen_id'      => $request->inventarioAlmacen_id,
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
                            'totalbs'               => $request->cantidad[$cont]*$request->precio[$cont],
                            'gestion'               => $request->gestion,
                            'sucursal_id'           => $request->sucursal_id
                    ]);


                    DetalleFactura::where('id',$request->detallefactura_id[$cont])->where('hist', 0)->decrement('cantrestante', $request->cantidad[$cont]);

                    // $aux = DetalleFactura::find($request->detallefactura_id[$cont]);       
                    $aux = DetalleFactura::where('id', $request->detallefactura_id[$cont])->where('hist', 0)->first();

                    $f = Factura::find($aux->factura_id);
                    $s = SolicitudCompra::find($f->solicitudcompra_id);
                    $s->update(['condicion' => 0]);

                    if($aux->cantrestante == 0)
                    {
                        DetalleFactura::where('id',$request->detallefactura_id[$cont])->where('hist', 0)->update(['condicion'=>0]);
                    }

                    $df = DetalleFactura::where('factura_id',$aux->factura_id)->where('hist', 0)->where('deleted_at', null)->get();
                    $ok= true;
                    $j = 0;
                    while($j < count($df))
                    {
                        if($df[$j]->cantrestante != 0)
                        {
                            $ok = false;
                        }
                        $j++;
                    }
                    if($ok)
                    {
                        SolicitudCompra::where('id',$f->solicitudcompra_id)->update(['condicion' => 0, 'stock' => 0]);
                    }
                    $cont++;
            }
            
            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function edit($id)
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return 1;
        
        // $user = Auth::user();
        // $sucursales = Sucursal::join('sucursal_users as u','u.sucursal_id', 'sucursals.id')
        //             ->select('sucursals.id','sucursals.nombre','u.condicion')
        //             ->where('u.condicion',1)
        //             ->where('u.user_id', $user->id)
        //             ->get();
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->get();

        if(count($sucursal) > 1 && count($sucursal) < 1)
        {
            return "Contactese con el administrador";
        }

        // $da = $this->getdireccion();
        $da = $this->direccionSucursal($sucursal->first()->sucursal_id);





        $solicitud = SolicitudEgreso::find($id);


        $detail = DB::table('detalle_egresos as de')
                    ->join('detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                    ->join('facturas as f', 'f.id', 'df.factura_id')
                    ->join('solicitud_compras as cp', 'cp.id', 'f.solicitudcompra_id')
                    ->join('modalities as m', 'm.id', 'cp.modality_id')
                    ->join('articles as a', 'a.id', 'df.article_id')
                    ->where('de.solicitudegreso_id', $solicitud->id)
                    ->where('de.deleted_at', null)
                    ->where('df.hist', 0)
                    ->select('de.id', 'de.detallefactura_id', 'de.cantsolicitada', 'de.precio', 'de.totalbs',
                    'de.gestion', 'de.condicion', 'm.nombre as modalidad', 'a.nombre as article', 'a.presentacion', 'cp.nrosolicitud')->get();

        return view('almacenes.egress.edit', compact('solicitud', 'detail', 'da', 'sucursal'));
    }

    public function update(Request $request)
    {
        // return $request;
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return $request;
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $egreso = SolicitudEgreso::find($request->id);

            $egreso->update(['sucursal_id'=>$request->branchoffice_id, 'unidadadministrativa'=>$request->unidadadministrativa, 'direccionadministrativa'=>$request->direccionadministrativa,
                'nropedido'=>$request->nropedido, 'fechasolicitud'=>$request->fechasolicitud, 'fechaegreso'=>$request->fechaegreso
            ]);

            $detalle = DetalleEgreso::where('solicitudegreso_id', $egreso->id)->where('deleted_at', null)->get();
            // return $detalle;
     
            $i=0;
            while($i < count($detalle))
            {
                DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->where('hist', 0)->increment('cantrestante', $detalle[$i]->cantsolicitada);

                $aux = DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->where('hist', 0)->first();

                $df = DetalleFactura::where('factura_id',$aux->factura_id)->where('hist', 0)->where('deleted_at', null)->get();
                $f = Factura::find($aux->factura_id);
                $s = SolicitudCompra::find($f->solicitudcompra_id);


                $j=0;
                $ok=true;
                while($j < count($df))
                {
                    if($df[$j]->cantsolicitada == $df[$j]->cantrestante)
                    {
                        $df[$j]->update(['condicion' => 1]);
                        $s->update(['stock' => 1]);

                    }
                    else
                    {
                        if($df[$j]->cantrestante > 0)
                        {
                            $df[$j]->update(['condicion' => 1]);
                            $s->update(['stock' => 1]);
                        }
                        $ok=false;
                    }
                    $j++;
                }
                if($ok)
                {           
                    $s->update(['condicion' => 1]);
                }

                
                $i++;

            }    
            DetalleEgreso::where('solicitudegreso_id', $egreso->id)->update(['deleted_at'=> Carbon::now()]);

            // return 1;
            $k=0;
            while($k< count($request->detallefactura_id))
            {
                if($request->detalle_id[$k] != 'NO')
                {   
                    $detal = DetalleEgreso::where('id', $request->detalle_id[$k])->where('detallefactura_id', $request->detallefactura_id[$k])->first();
                    if(!$detal)
                    {
                        return redirect()->route('egres.index')->with(['message' => 'Contactese con el administrador.', 'alert-type' => 'error']);
                    }
                    // return $detal;
                    DetalleEgreso::where('id', $request->detalle_id[$k])->update(['deleted_at' => null]);



                    // return $detal;

                    DetalleFactura::where('id',$detal->detallefactura_id)->where('hist', 0)->decrement('cantrestante', $detal->cantsolicitada);
                    // return 1;

                    // $aux = DetalleFactura::find($detal->detallefactura_id);     
                    $aux = DetalleFactura::where('id', $detal->detallefactura_id)->where('hist', 0)->first();

                    $f = Factura::find($aux->factura_id);
                    $s = SolicitudCompra::find($f->solicitudcompra_id);
                    // return 1;

                    $s->update(['condicion' => 0]);

                    if($aux->cantrestante == 0)
                    {
                        $aux->update(['condicion'=>0]);
                    }

                    $df = DetalleFactura::where('factura_id',$aux->factura_id)->where('deleted_at', null)->where('hist', 0)->get();
                    $ok= true;
                    $m = 0;
                    // return 1;

                    while($m < count($df))
                    {
                        if($df[$m]->cantrestante != 0)
                        {
                            $ok = false;
                            
                        }
                        $m++;
                    }
                    if($ok)
                    {
                        SolicitudCompra::where('id',$f->solicitudcompra_id)->update(['condicion' => 0, 'stock' => 0]);
                    }
                    
                    // return 1;

                }
                else
                {
                    
                    DetalleEgreso::create([
                        'solicitudegreso_id'    => $egreso->id,
                        'registeruser_id'       => $user->id,
                        'detallefactura_id'     => $request->detallefactura_id[$k],
                        'cantsolicitada'        => $request->cantidad[$k],
                        'precio'                => $request->precio[$k],
                        'totalbs'               => $request->cantidad[$k]*$request->precio[$k],
                        'gestion'               => $egreso->gestion
                    ]);


                    DetalleFactura::where('id',$request->detallefactura_id[$k])->where('hist', 0)->decrement('cantrestante', $request->cantidad[$k]);

                    // $aux = DetalleFactura::find($request->detallefactura_id[$k]); 
                    $aux = DetalleFactura::where('id', $request->detallefactura_id[$k])->where('hist', 0)->first();

                    $f = Factura::find($aux->factura_id);
                    $s = SolicitudCompra::find($f->solicitudcompra_id);

                    $s->update(['condicion' => 0]);

                    if($aux->cantrestante == 0)
                    {
                        $aux->update(['condicion'=>0]);
                    }
                    $df = DetalleFactura::where('factura_id',$aux->factura_id)->where('hist', 0)->where('deleted_at', null)->get();
                    $ok= true;
                    $m = 0;
                    while($m < count($df))
                    {
                        if($df[$m]->cantrestante != 0)
                        {
                            $ok = false;

                        }
                        $m++;
                    }
                    // return 1;

                    if($ok)
                    {
                        SolicitudCompra::where('id',$f->solicitudcompra_id)->update(['condicion' => 0, 'stock' => 0]);
                    }
                }
                $k++;

            }
            // return 1010110;



            // return 2;
            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }



    public function destroy(Request $request)
    {       
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return $request;
        $user =Auth::user();
        DB::beginTransaction();
        try{

            $sol = SolicitudEgreso::find($request->id);
            SolicitudEgreso::where('id', $sol->id)->update(['deleteuser_id'=>$user->id, 'deleted_at' => Carbon::now()]);
    
           

            $detalle = DetalleEgreso::where('solicitudegreso_id', $sol->id)->where('deleted_at', null)->where('condicion',1)->get();
            $i=0;

            while($i < count($detalle))
            {                
                DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->where('hist', 0)->increment('cantrestante', $detalle[$i]->cantsolicitada);

                // $aux = DetalleFactura::find($detalle[$i]->detallefactura_id);
                $aux = DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->where('hist', 0)->first();


                $df = DetalleFactura::where('factura_id',$aux->factura_id)->where('deleted_at', null)->where('hist', 0)->get();
                $f = Factura::find($aux->factura_id);
                $s = SolicitudCompra::find($f->solicitudcompra_id);
                $j=0;
                $ok=true;
                while($j < count($df))
                {
                    if($df[$j]->cantsolicitada == $df[$j]->cantrestante)
                    {
                        $df[$j]->update(['condicion' => 1]);
                        $s->update(['stock' => 1]);

                    }
                    else
                    {
                        if($df[$j]->cantrestante > 0)
                        {
                            $df[$j]->update(['condicion' => 1]);
                            $s->update(['stock' => 1]);
                        }
                        $ok=false;
                    }
                    $j++;
                }
                if($ok)
                {           
                    $s->update(['condicion' => 1]);
                }

                
                $i++;
            }


            DetalleEgreso::where('solicitudegreso_id', $sol->id)->update(['deleteuser_id'=>$user->id, 'deleted_at' => Carbon::now()]);


            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
            

    }//anulado por mantenimiento


    protected function show($id)
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return $id;
        $sol = SolicitudEgreso::with(['sucursal'])
            ->where('id', $id)->first();

        // return $sol;

       
        $unidad = DB::connection('mamore')->table('unidades')
                ->select('nombre')
                ->where('id', $sol->unidadadministrativa)
                ->get();

        $detalle = DB::table('solicitud_egresos as s')
                ->join('detalle_egresos as sd', 'sd.solicitudegreso_id', 's.id')
                ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
                ->join('articles as a', 'a.id', 'df.article_id')
                // ->join('solicitud_compras as sc', 'sc.id', 'sd.solicitudcompra_id')
                // ->join('modalities as m', 'm.id', 'sc.modality_id')
                ->select('a.nombre as articulo', 's.nropedido as numero', 'a.id as codigo', 'a.presentacion', 'sd.cantsolicitada','df.precio')
                ->where('s.id',  $sol->id)
                ->where('sd.deleted_at', null)
                ->where('df.hist', 0)
                ->get();

        // return $detalle;


        
        return view('almacenes.egress.report', compact('sol', 'unidad', 'detalle'));
    }


    
    // ###################################################################################################################################################
    // ###################################################################################################################################################
    // ###################################################################################################################################################
    // ################################################################    SOLICITUD    ##################################################################
    // ###################################################################################################################################################
    // ###################################################################################################################################################
    // ###################################################################################################################################################

    public function showSolicitud($id)
    {
        // return 1;
        $data = SolicitudPedido::with('solicitudDetalle', 'sucursal')
            ->where('deleted_at', null)
            ->where('id', $id)
            ->first();
        if($data->entregadoVisto == null)
        {
            $data->update(['entregadoVisto'=>Carbon::now()]);

        }

        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        // return $sucursal;
        $unidad = SucursalUnidadPrincipal::where('deleted_at', null)->where('sucursal_id', $sucursal->sucursal_id)->first()->unidadAdministrativa_id??'';
        // return $unidad;
        // SolicitudPedido::where('id', $id)->update(['visto'=>Carbon::now()]);
        return view('almacenes.egress.readSolicitud', compact('data', 'unidad'));
    }

    // Para obtener todos los articulos comprado o ingresado por la unidad
    public function ajax_unidad($unidad, $article)
    {
        $data = DB::table('solicitud_compras as s')
            ->join('facturas as f', 'f.solicitudcompra_id', 's.id')
            ->join('detalle_facturas as d', 'd.factura_id', 'f.id')
            ->join('articles as a', 'a.id', 'd.article_id')
            ->where('s.stock', 1)
            ->where('s.deleted_at', null)
            ->where('s.unidadadministrativa', $unidad)

            ->where('f.deleted_at', null)

            ->where('d.deleted_at', null)
            ->where('d.cantrestante', '>', 0)
            ->where('d.condicion', 1)
            ->where('d.hist', 0)

            ->where('a.id', $article)

            ->select('s.id as solicitud_id', 's.nrosolicitud', 'f.id as factura_id', 'a.id as article_id', 'a.nombre as article','d.id as detalle_id', 'd.precio', 'd.cantrestante as cantidad')
            // ->groupBy('article_id')
            ->orderBy('s.id')
            ->get();

        return $data;
    }
    //para obtener los articulos del almacen central de cada almacen u sucursal
    public function ajax_almacen($article)
    {
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        $unidad = SucursalUnidadPrincipal::where('deleted_at', null)->where('sucursal_id', $sucursal->sucursal_id)->first()->unidadAdministrativa_id;
        // return $unidad;
        $data = DB::table('solicitud_compras as s')
            ->join('facturas as f', 'f.solicitudcompra_id', 's.id')
            ->join('detalle_facturas as d', 'd.factura_id', 'f.id')
            ->join('articles as a', 'a.id', 'd.article_id')
            ->where('s.stock', 1)
            ->where('s.deleted_at', null)
            ->where('s.unidadadministrativa', $unidad)

            ->where('f.deleted_at', null)

            ->where('d.deleted_at', null)
            ->where('d.cantrestante', '>', 0)
            ->where('d.condicion', 1)
            ->where('d.hist', 0)

            ->where('a.id', $article)

            ->select('s.id as solicitud_id', 's.nrosolicitud', 'f.id as factura_id', 'a.id as article_id', 'a.nombre as article', 'd.id as detalle_id', 'd.precio', 'd.cantrestante as cantidad')
            // ->groupBy('article_id')
            ->orderBy('s.id')
            ->get();

        return $data;
    }

    // para poder guardar el detalle de cada 
    public function detailsDetalle(Request $request)
    {
        DB::beginTransaction();
        try {
            $detalle = SolicitudPedidoDetalle::where('solicitudPedido_id', $request->id)->where('id', $request->detalle_id)->first();
            if(!$request->cantidad)
            {
                $detalle->update(['cantentregada'=>0, 'jsonDetails_id'=>null]);
                DB::commit();
                return response()->json(['detalle' => $detalle->id]);
            }
            $sum = 0;
            for ($i=0; $i < count($request->cantidad) ; $i++) { 
                $sum+=$request->cantidad[$i];
            }
            $detalle->update(['cantentregada'=>$sum, 'jsonDetails_id'=>json_encode(['almacen'=>$request->almacen, 'detalle_id'=>$request->detalle, 'cantidad'=>$request->cantidad]) ]);
            DB::commit();
            return response()->json(['detalle' => $detalle]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    // para rechazar las solicituded de pedido ante de dispensar el producto
    public function rechazarSolicitud(Request $request)
    {
        SolicitudPedido::where('id', $request->id)->update(['status'=>'Rechazado', 'rechazadoUser_id'=>Auth::user()->id, 'rechazadoDate'=>Carbon::now()]);
        return redirect()->route('egres.index')->with(['message' => 'Solicitud rechazada exitosamente.', 'alert-type' => 'success']);
    }

    //Para dispensar el producto de cada almacen siempre que este aprobado el pedido
    public function entregarSolicitud(Request $request)
    {
        // return 1;
        DB::beginTransaction();
        try {
            $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
            $unidad = SucursalUnidadPrincipal::where('deleted_at', null)->where('sucursal_id', $sucursal->sucursal_id)->first()->unidadAdministrativa_id??'';
            $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada         

            $solicitud = SolicitudPedido::where('id', $request->id)->where('deleted_at',null)->first();
            $detalle = SolicitudPedidoDetalle::where('solicitudPedido_id', $solicitud->id)->where('deleted_at',null)->get();

           
            if($solicitud->status != 'Aprobado')
            {
                return redirect()->route('egres.index')->with(['message' => 'El Pedido no se encuentra disponible', 'alert-type' => 'error']);
            }

            if(!$gestion)
            {
                return redirect()->route('egres-solicitud.show',['solicitud'=>$solicitud->id])->with(['message' => 'Ocurrio un error. No tiene una gestion activa en su almacen', 'alert-type' => 'error']);                                    
            }
            if($solicitud->status == 'Entregado')
            {
                return redirect()->route('egres.index')->with(['message' => 'La solicitud ya fue entregada', 'alert-type' => 'info']);                                    
            }
            // return $gestion;


            $detalle_id = [];
            $cant = [];
            $user = Auth::user();
            foreach($detalle as $item)
            {
                if($item->jsonDetails_id)
                {
                    $i=0;
                    foreach(json_decode($item->jsonDetails_id) as $item1)
                    {
                        // return $item->jsonDetails_id;
                        if($i==0)
                        {
                            foreach($item1 as $data)
                            {
                                if($data == 'si')
                                {

                                    if($solicitud->unidad_id==$unidad)
                                    {
                                        return redirect()->route('egres-solicitud.show',['solicitud'=>$solicitud->id])->with(['message' => 'Ocurrio un error. Agrege nuevamente los detalle de la solicitud', 'alert-type' => 'error']);                                    
                                    }
                                }
                            }
                        }
                        $i++;                        
                    }
                }
            }
            foreach($detalle as $item)
            {
                if($item->jsonDetails_id)
                {
                    $i=0;
                    foreach(json_decode($item->jsonDetails_id) as $item1)
                    {
                        if($i==1)
                        {
                            $detalle_id = $item1;                            
                        }
                        if($i==2)
                        {
                            $cant = $item1;                            
                        }
                        $i++;                        
                    }
                    // return $cant;
                    for ($x=0; $x < count($detalle_id); $x++) { 
                        $verf = DetalleFactura::where('id', $detalle_id[$x])->where('deleted_at', null)->where('hist', 0)->first();
                        // return $verf;
                        if($verf->cantrestante<0)
                        {
                            return redirect()->route('egres-solicitud.show',['solicitud'=>$solicitud->id])->with(['message' => 'Ocurrio un error. Contactese con el administrador de sistema', 'alert-type' => 'error']);                                    
                        }
                        if($cant[$x] > $verf->cantrestante)
                        {
                            return redirect()->route('egres-solicitud.show',['solicitud'=>$solicitud->id])->with(['message' => 'Ocurrio un error. Algun articulo supera la cantidad de stock existente', 'alert-type' => 'error']);                                    
                        }
                    }
                }
            }
            // return $solicitud;
            $egreso=SolicitudEgreso::create([
                'sucursal_id'               => $solicitud->sucursal_id,
                'unidadadministrativa'      => $solicitud->unidad_id,
                'direccionadministrativa'   => $solicitud->direccion_id,
                'registeruser_id'           => $user->id,
                'nropedido'                 => $solicitud->nropedido,
                'fechasolicitud'            => $solicitud->fechasolicitud,
                'fechaegreso'               => Carbon::now(),
                'gestion'                   => $gestion->gestion,
                'inventarioAlmacen_id'      => $gestion->id,
                'solicitudPedido_id'        => $solicitud->id
            ]);
            // return 1;
            // return $detalle->SUM('cantentregada');
            if($detalle->SUM('cantentregada')==0)
            {
                return redirect()->route('egres.index')->with(['message' => 'Todo el detalle se encuentra sin cantidad a entregar.', 'alert-type' => 'warning']);
            }
            // return $detalle;
            foreach($detalle as $item)
            {
                if($item->jsonDetails_id)
                {
                    $i=0;
                    foreach(json_decode($item->jsonDetails_id) as $item1)
                    {
                        // return $detalle;
                        // return $item->jsonDetails_id;
                        if($i==1)
                        {
                            $detalle_id = $item1;                            
                        }
                        if($i==2)
                        {
                            $cant = $item1;                            
                        }
                        $i++;                        
                    }
                    // return 1;
                    // return $item->jsonDetails_id;
                    // return $detalle_id;
                    // return $cant;

                    for ($x=0; $x < count($detalle_id); $x++) { 
                        // return $cant[$x];
                        if($cant[$x] > 0)
                        {
                            // return $cant[$x];
                            $verf = DetalleFactura::where('id', $detalle_id[$x])->where('deleted_at', null)->where('hist', 0)->first();

                            DetalleEgreso::create([
                                'solicitudegreso_id'    => $egreso->id,
                                'registeruser_id'       => $user->id,
                                'detallefactura_id'     => $detalle_id[$x],
                                'cantsolicitada'        => $cant[$x],
                                'precio'                => $verf->precio,
                                'totalbs'               => $cant[$x]*$verf->precio,
                                'gestion'               => $gestion->gestion,
                                'sucursal_id'           => $solicitud->sucursal_id
                            ]);
                            $verf->decrement('cantrestante', $cant[$x]);

                            $f = Factura::find($verf->factura_id);
                            $s = SolicitudCompra::find($f->solicitudcompra_id);
                            $s->update(['condicion' => 0]);

                            if($verf->cantrestante == 0)
                            {
                                $verf->update(['condicion'=>0]);
                            }

                            $df = DetalleFactura::where('factura_id',$verf->factura_id)->where('hist', 0)->where('deleted_at', null)->get();
                            $ok= true;
                            $j = 0;
                            while($j < count($df))
                            {
                                if($df[$j]->cantrestante != 0)
                                {
                                    $ok = false;
                                }
                                $j++;
                            }
                            if($ok)
                            {
                                SolicitudCompra::where('id',$f->solicitudcompra_id)->update(['condicion' => 0, 'stock' => 0]);
                            }
                        }
                    }
                }

            }
            $solicitud->update(['status'=>'Entregado', 'entregadoDate'=>Carbon::now(), 'entregadoUser_id'=>Auth::user()->id]);
            DB::commit();
            return redirect()->route('egres.index')->with(['message' => 'Solicitud entregada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }













































   

    // metodo para buscar las compras de la unidad correspondiente
    protected function ajax_solicitud_compra($id)
    {
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();

        $query_filter = 'com.sucursal_id = '.$sucursal->sucursal_id;
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        if($sucursal->sucursal_id == 1)
        {
            // $query_filter = 1;
            $solicitud = DB::table('solicitud_compras as com')
            ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
            ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
            ->join('modalities as m', 'm.id', 'com.modality_id')
            ->select('com.id', 'm.nombre', 'com.nrosolicitud')
            ->where('fd.hist',0)
            ->where('fd.condicion', 1)
            ->where('f.condicion', 1)
            // ->where('com.unidadadministrativa', $id)
            ->whereRaw('com.unidadadministrativa ='.$id.' or com.unidadadministrativa = 192')

            ->where('com.sucursal_id', $sucursal->sucursal_id)

        
            ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
            ->orderBy('com.id')
            ->get();
        }
        else
        {
            $solicitud = DB::table('solicitud_compras as com')
            ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
            ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
            ->join('modalities as m', 'm.id', 'com.modality_id')
            ->select('com.id', 'm.nombre', 'com.nrosolicitud')
            // ->where('com.stock',0)
            ->where('fd.hist',0)
            ->where('fd.condicion', 1)
            ->where('f.condicion', 1)
            // ->where('com.unidadadministrativa', $id)
            ->whereRaw('com.unidadadministrativa ='.$id)
            ->where('com.sucursal_id', $sucursal->sucursal_id)

        
            ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
            ->orderBy('com.id')
            ->get();
        }


        $solicitud = DB::table('solicitud_compras as com')
                ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
                ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
                ->join('modalities as m', 'm.id', 'com.modality_id')
                ->select('com.id', 'm.nombre', 'com.nrosolicitud')
                
                ->where('com.sucursal_id', $sucursal->sucursal_id)
                
                ->where('com.unidadadministrativa',$id)


                ->where('com.deleted_at', null)

                ->where('f.deleted_at', null)

                ->where('fd.deleted_at', null)
                ->where('fd.hist',0)
                ->where('fd.cantrestante', '>', 0)
            
                ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
                ->orderBy('com.id')
                ->get();

        if($sucursal->sucursal_id == 1)
        {
            $solicitud = DB::table('solicitud_compras as com')
                ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
                ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
                ->join('modalities as m', 'm.id', 'com.modality_id')
                ->select('com.id', 'm.nombre', 'com.nrosolicitud')
                
                ->where('com.sucursal_id', $sucursal->sucursal_id)
                
                ->whereRaw('com.unidadadministrativa ='.$id.' or com.unidadadministrativa = 192')


                ->where('com.deleted_at', null)

                ->where('f.deleted_at', null)

                ->where('fd.deleted_at', null)
                ->where('fd.hist',0)
                ->where('fd.cantrestante', '>', 0)
            
                ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
                ->orderBy('com.id')
                ->get();
        }

        if($sucursal->sucursal_id == 13)
        {
            $solicitud = DB::table('solicitud_compras as com')
                ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
                ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
                ->join('modalities as m', 'm.id', 'com.modality_id')
                ->select('com.id', 'm.nombre', 'com.nrosolicitud')
                
                ->where('com.sucursal_id', $sucursal->sucursal_id)
                
                ->whereRaw('com.unidadadministrativa ='.$id.' or com.unidadadministrativa = 221')


                ->where('com.deleted_at', null)

                ->where('f.deleted_at', null)

                ->where('fd.deleted_at', null)
                ->where('fd.hist',0)
                ->where('fd.cantrestante', '>', 0)
            
                ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
                ->orderBy('com.id')
                ->get();
        }


        if($sucursal->sucursal_id == 6)
        {
            $solicitud = DB::table('solicitud_compras as com')
                ->join('facturas as f', 'f.solicitudcompra_id', 'com.id')
                ->join('detalle_facturas as fd', 'fd.factura_id', 'f.id')
                ->join('modalities as m', 'm.id', 'com.modality_id')
                ->select('com.id', 'm.nombre', 'com.nrosolicitud')
                
                ->where('com.sucursal_id', $sucursal->sucursal_id)
                
                ->whereRaw('com.unidadadministrativa ='.$id.' or com.unidadadministrativa = 304')


                ->where('com.deleted_at', null)

                ->where('f.deleted_at', null)

                ->where('fd.deleted_at', null)
                ->where('fd.hist',0)
                ->where('fd.cantrestante', '>', 0)
            
                ->groupBy('com.id', 'm.nombre', 'com.nrosolicitud')
                ->orderBy('com.id')
                ->get();
        }
        
        

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
                    ->where('fd.hist', 0)
                    ->where('fd.condicion', 1)
                    ->where('fd.cantrestante', '>', 0)

                    ->where('fd.deleted_at', null)
                    ->where('com.id', $id)
                    ->get();
        return $articulo;
        
    }


    public function ajax_egres_select_article_detalle($id)
    {
        
        // return Article::where('id', $id)->first();
        $detalle = DB::table('detalle_facturas as fd')
                    ->join('articles as a', 'a.id', 'fd.article_id')
                    ->select('fd.id', 'a.presentacion', 'fd.precio', 'fd.cantrestante')
                    ->where('fd.id', $id)
                    ->where('fd.hist', 0)
                    ->get();
        return $detalle;
    }

}


    
