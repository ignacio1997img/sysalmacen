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

class EgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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


    public function list($search = null){
        $user = Auth::user();

        $sucursal = SucursalUser::where('user_id', $user->id)->where('condicion', 1)->where('deleted_at', null)->first();
        $gestion = InventarioAlmacen::where('status', 1)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada

        $query_filter = 'sucursal_id = '.$sucursal->sucursal_id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }

        
        $paginate = request('paginate') ?? 10;
        
        $data = SolicitudEgreso::with(['sucursal', 'unidad', 'direccion'])
            ->where('deleted_at', NULL)
            ->whereRaw($query_filter)
            ->orderBy('id', 'DESC')->paginate($paginate);

        // dd($data);
        
      

        return view('almacenes.egress.list', compact('data', 'gestion'));
    }

    // public function view_pendiente($id)
    // {
    //     $solicitud =  Solicitud::find($id);

    //     $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
    //             ->select('Nombre')
    //             ->where('id', $solicitud->unidadadministrativa)
    //             ->get();
    //     // return $unidad;

    //     $detalle = DB::table('solicitud_detalles as sd')
    //             ->join('detalle_facturas as df', 'df.id', 'sd.detallefactura_id')
    //             ->join('articles as a', 'a.id', 'df.article_id')
    //             ->join('partidas as p', 'p.id', 'a.partida_id')
    //             ->select('p.codigo', 'a.nombre', 'a.presentacion', 'sd.cantidad','sd.cantidadentregar')
    //             ->where('sd.solicitud_id', $solicitud->id)
    //             ->get();

    //     $derivacion = SolicitudDerivada::where('solicitud_id', $solicitud->id)->get();
    //     // return $derivacion;


    //     return view('egress.viewegresopendiente', compact('solicitud', 'unidad', 'detalle', 'derivacion'));
    //     // return view('egress.viewegresopendiente');
    // }

    // public function store_egreso_pendiente(Request $request)
    // {
    //     // return $request;

    //     DB::beginTransaction();
    //     try {

    //         $ok = SolicitudDetalle::where('solicitud_id', $request->solicitud_id)->get();
            
    //         $aux = true;
    //         foreach($ok as $data)
    //         {
    //             $def = DetalleFactura::find($data->detallefactura_id);
                
    //             if($data->cantidadentregar > $def->cantrestante)
    //             {
    //                 // return DetalleFactura::find($data->detallefactura_id);
    //                 $aux=false;
    //                 $df = DetalleFactura::find($data->detallefactura_id);
    //                 $articulo = Article::find($df->article_id);
    //                 // return $articulo;
    //                 return redirect()->route('egres.index')->with(['message' => 'La cantidad que intenta sacar es mayor a la cantidad de Stock de '. $articulo->nombre.' que tiene disponible el almacen.', 'alert-type' => 'error']);
    //             }
    //         }

    //         if($aux == true)
    //         {
    //             $user = Auth::user();
    //             $funcionarios = DB::connection('mysqlgobe')->table('contribuyente as co')                            
    //                 ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
    //                 ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
    //                 ->join('cargo as ca', 'ca.ID', 'c.idCargo')
    //                 ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
    //                 ->where('c.Estado', 1)
    //                 ->where('c.ID',$user->funcionario_id)
    //                 ->get();

    //             SolicitudDerivada::where('solicitud_id', $request->solicitud_id)->update(['atendido' => 1]);
    //             Solicitud::where('id', $request->solicitud_id)->update(['estado' => 'Entregado', 'atendidopor'=> $funcionarios[0]->nombrecontribuyente.' - '.$funcionarios[0]->cargo]);

    //             $detalle = SolicitudDetalle::where('solicitud_id', $request->solicitud_id)->get();
                
            
    //             foreach($detalle as $data)
    //             {
    //                 DetalleFactura::where('id',$data->detallefactura_id)->decrement('cantrestante', $data->cantidadentregar);

    //                 $d = DetalleFactura::find($data->detallefactura_id);
    //                 if($d->cantrestante == 0)
    //                 {
    //                     DetalleFactura::where('id',$data->detallefactura_id)->update(['condicion'=>0]);
    //                 }

    //                 $detalle = DetalleFactura::find($data->detallefactura_id);
    //                 $factura = Factura::find($detalle->factura_id);
    //                 SolicitudCompra::where('id',$factura->solicitudcompra_id)->update(['condicion' => 0]);

    //             }              


    //             DB::commit();
    //             return redirect()->route('egres.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
    //         }
    //         else
    //         {
    //             return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
    //         }

    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return redirect()->route('egres.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
    //     }
    // }








    // en mantenimineto los metodos

    public function create()
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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

        // $user = Auth::user();
        // $sucursales = Sucursal::join('sucursal_users as u','u.sucursal_id', 'sucursals.id')
        //             ->select('sucursals.id','sucursals.nombre','u.condicion')
        //             ->where('u.condicion',1)
        //             ->where('u.user_id', $user->id)
        //             ->get();

        // $da = $this->getdireccion(); 
        // return $sucursal;
        $da = $this->direccionSucursal($sucursal->sucursal_id);
        // return $da;
        $sucursal = Sucursal::where('id', $sucursal->sucursal_id)->first();
        // return $sucursal;


        return view('almacenes.egress.add', compact('sucursal', 'da', 'gestion'));
    }//anulado 

 
    public function store(Request $request)
    {
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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
            // return 1;
            
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
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }
        // return $request;
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $egreso = SolicitudEgreso::find($request->id);
            
            // return $egreso;
            $egreso->update(['sucursal_id'=>$request->branchoffice_id, 'unidadadministrativa'=>$request->unidadadministrativa, 'direccionadministrativa'=>$request->direccionadministrativa,
                'nropedido'=>$request->nropedido, 'fechasolicitud'=>$request->fechasolicitud, 'fechaegreso'=>$request->fechaegreso
            ]);
            // return $egreso;

            // DetalleEgreso::where('solicitudegreso_id',$egreso->id)->update(['deleted_at'=>Carbon::now()]);
            $detalle = DetalleEgreso::where('solicitudegreso_id', $egreso->id)->where('deleted_at', null)->get();
     
            $i=0;
            while($i < count($detalle))
            {
                DetalleFactura::where('id', $detalle[$i]->detallefactura_id)->where('hist', 0)->increment('cantrestante', $detalle[$i]->cantsolicitada);

                // $aux = DetalleFactura::find($detalle[$i]->detallefactura_id);
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
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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
        if(setting('configuracion.maintenance')&& !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin') && Auth::user()->id != 18 && Auth::user()->id != 33 && Auth::user()->id != 3)
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


    protected function ajax_egres_select_article_detalle($id)
    {
        $detalle = DB::table('detalle_facturas as fd')
                    ->join('articles as a', 'a.id', 'fd.article_id')
                    ->select('fd.id', 'a.presentacion', 'fd.precio', 'fd.cantrestante')
                    ->where('fd.id', $id)
                    ->where('fd.hist', 0)
                    ->get();
        return $detalle;
    }

}


    
