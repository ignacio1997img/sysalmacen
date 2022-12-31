<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SolicitudCompra;
use App\Models\Article;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\InventarioAlmacen;
use Faker\Provider\ar_JO\Company;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\SucursalUser;
use App\Models\Direction;
use App\Models\Provider;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AnualPartidaExport;
use App\Exports\AnualDaExport;
use App\Exports\AnualDetalleExport;
use App\Exports\ArticleStockExport;
use App\Exports\ProviderListExport;
use App\Exports\ArticleListExport;
use App\Exports\ArticleIncomeOfficeExport;
use App\Exports\ArticleEgressOfficeExport;
use App\Models\DetalleEgreso;
use App\Models\SolicitudEgreso;
use App\Models\Unit;

class ReportAlmacenController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //para los reportes mediantes direciones admistrativa Income y Egress en Bolivianos  saldo
    public function directionIncomeSalida()
    {

//     $data = DB::table('solicitud_compras as s')
//     ->join('facturas as f', 'f.solicitudcompra_id', 's.id')
//     ->join('detalle_facturas as d', 'd.factura_id', 'f.id')

//     ->where('s.gestion', 2022)
//     ->where('s.sucursal_id', 1)
//     // ->where('s.deleted_at', null)

//     ->where('f.deleted_at', null)


//     ->where('d.deleted_at', null)
//     ->where('d.hist', 0)

//     // ->select(DB::raw("SUM(d.cantsolicitada * d.precio) as ingreso"))
//     ->select('s.id', DB::raw("SUM(d.cantsolicitada * d.precio) as ingreso"), 'd.totalbs')
//     ->groupBy('s.id')
//     ->get();

// // _____________________________________________________________________________________________
//     $data = DB::table('solicitud_compras as s')
//     ->join('facturas as f', 'f.solicitudcompra_id', 's.id')

//     ->where('s.gestion', 2022)
//     ->where('s.sucursal_id', 1)
//     ->where('s.deleted_at', null)

//     ->where('f.deleted_at', null)

//     ->select('s.id', 'f.id as factura', DB::raw("SUM(f.montofactura) as ingreso"))
//     ->groupBy('s.id')
//     ->get();
//     // return $data;
    



//     foreach($data as $item)
//     {
//         // return $item;
//         $ok = DB::table('detalle_facturas as d')

//             ->where('d.factura_id', $item->factura)


//             ->where('d.deleted_at', null)
//             ->where('d.hist', 0)

//             // ->select(DB::raw("SUM(d.cantsolicitada * d.precio) as ingreso"))
//             ->select(DB::raw("SUM(d.cantsolicitada * d.precio) as ingreso"), DB::raw("SUM(d.totalbs) as totalbs"))
//             ->get();
//         // return $ok[0]->ingreso;
//         // return $item;


//         if($item->ingreso != $ok[0]->totalbs)
//         {
//             return $item->id;
//             // return $ok[0]->totalbs;
//             return $item->ingreso;
//         }
//     }


// para agregar las direciones 
        // $ok = SolicitudEgreso::all();

        // foreach($ok as $item)
        // {
        //     SolicitudEgreso::where('id',$item->id)->update(['direccionadministrativa'=>$this->getUnidad($item->unidadadministrativa)->direccion_id]);
        // }


        $gestion = InventarioAlmacen::where('deleted_at', null)->where('status', 0)->get();


        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();       

        return view('almacenes/report/inventarioAnual/direccionAdministrativa/report', compact('sucursal','gestion'));
    }
    public function directionIncomeSalidaList(Request $request)
    {
        $gestion = $request->gestion;
        $sucursal = Sucursal::find($request->sucursal_id);


        // para obtener todas las direciones de cada almacen
        $direction = DB::connection('mamore')->table('direcciones as d')
            ->join('sysalmacen.sucursal_direccions as s', 's.direccionAdministrativa_id', 'd.id')
            ->where('s.deleted_at', null)
            ->where('s.sucursal_id', $request->sucursal_id)

            ->select('d.id as direcion_id', 'd.nombre')
            ->orderBy('d.id', 'ASC')

            ->get();

        
        //Para obtener los saldos de cada almacen de las GESTIONES anteriores 
        $saldos = DB::table('solicitud_compras as sc')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                ->join('detalle_facturas as df', 'df.factura_id', 'f.id')  

                ->where('sc.deleted_at', null)
                ->where('sc.sucursal_id', $request->sucursal_id)

                ->where('f.deleted_at', null)

                ->where('df.hist', 1)
                ->where('df.gestion', $gestion)
                ->where('df.deleted_at', null)
                

                ->select('sc.direccionadministrativa as id', DB::raw("SUM(df.cantrestante * df.precio) as saldo"))
                ->groupBy('sc.direccionadministrativa')
                ->orderBy('sc.direccionadministrativa', 'ASC')
                ->get();



        // Para obtener los ingresos de la gestion actual de cada almacen
        $data = DB::table('solicitud_compras as sc')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                ->join('detalle_facturas as df', 'df.factura_id', 'f.id')   

                ->where('sc.deleted_at', null)
                ->where('sc.sucursal_id', $request->sucursal_id)
                ->where('sc.gestion', $gestion)

                ->where('f.deleted_at', null)

                ->where('df.hist', 0)
                ->where('df.gestion', $gestion)
                ->where('df.deleted_at', null)                    

                ->select('sc.direccionadministrativa as id', DB::raw("SUM(df.cantsolicitada * df.precio) as ingreso"))
                    // ->select('sc.direccionadministrativa as id',DB::raw("SUM(f.montofactura) as ingreso"))
                ->groupBy('sc.direccionadministrativa')
                ->get();

                 
        // Para obtener las salidas de la gestion  actual de cada almacen
        $salida = DB::table('solicitud_egresos as se')
                ->join('detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')

                ->where('se.gestion', $gestion)
                ->where('se.sucursal_id', $request->sucursal_id)
                ->where('se.deleted_at', null)

                ->where('de.deleted_at', null)
                        // ->where('d.direcciones_tipo_id', 1)
                ->select('se.direccionadministrativa as id', DB::raw("SUM(de.cantsolicitada * de.precio) as salida"))
                        // ->select('d.id',DB::raw("SUM(de.totalbs) as salida"))

                ->groupBy('se.direccionadministrativa')
                ->get();

        
            foreach($direction as $item)
            {
                $item->inicio="0.0";

                foreach($saldos as $sitem)
                {
                    if($item->direcion_id == $sitem->id)
                    {
                        $item->inicio = $sitem->saldo;
                    }
                }


                $item->ingreso="0.0";
                foreach($data as $ditem)
                {
                    if($item->direcion_id == $ditem->id)
                    {
                        $item->ingreso = $ditem->ingreso;
                    }
                }

                $item->salida="0.0";
                foreach($salida as $eitem)
                {
                    if($item->direcion_id == $eitem->id)
                    {
                        $item->salida = $eitem->salida;
                    }
                }
            }



        if($request->print==1)
        {
            return view('almacenes/report/inventarioAnual/direccionAdministrativa/print', compact('direction', 'gestion', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new AnualDaExport($data), $sucursal->nombre.' - DA Anual '.$gestion.'.xlsx');
        }
        if($request->print ==NULL)
        {            
            return view('almacenes/report/inventarioAnual/direccionAdministrativa/list', compact('direction'));
        }
    }

    // ################################################################################
    // para ver el inventario por partida anual
    public function inventarioPartida()
    { 
        // para obtener las gestiones disponible
        $gestion = InventarioAlmacen::where('deleted_at', null)->where('status', 0)->get();


        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();  

        return view('almacenes/report/inventarioAnual/partidaGeneral/report', compact('sucursal', 'gestion'));
    }

    public function inventarioPartidaList(Request $request)
    {
        // dd($request);
        $gestion = $request->gestion;
        // return $gestion;
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        // Para obtener el saldo de la anterior gestion
        $saldo = DB::table('solicitud_compras as sc')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                ->join('detalle_facturas as df', 'df.factura_id', 'f.id')   

                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')

                ->where('sc.deleted_at', null)
                ->where('sc.sucursal_id', $request->sucursal_id)

                ->where('f.deleted_at', null)

                ->where('df.hist', 1)
                ->where('df.gestion', $gestion)
                ->where('df.deleted_at', null)                    

                ->select('p.id', 'p.nombre', DB::raw("SUM(df.cantrestante * df.precio) as s_inicialbs"), DB::raw("SUM(df.cantrestante) as s_inicialc"))
                ->groupBy('p.id')
                ->get();
        // return $saldo;
        // dd($saldo);



        $ingreso = DB::table('solicitud_compras as sc')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                ->join('detalle_facturas as df', 'df.factura_id', 'f.id')   

                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')

                ->where('sc.deleted_at', null)
                ->where('sc.sucursal_id', $request->sucursal_id)

                ->where('f.deleted_at', null)

                ->where('df.hist', 0)
                ->where('df.gestion', $gestion)
                ->where('df.deleted_at', null)                    

                ->select('p.id', DB::raw("SUM(df.cantsolicitada * df.precio) as ingreso"), DB::raw("SUM(df.cantsolicitada) as s_inicialc"))
                // ->groupBy('p.id')

                ->get();
        // return $ingreso;



        $restante = DB::table('solicitud_compras as sc')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                ->join('detalle_facturas as df', 'df.factura_id', 'f.id')   

                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')

                ->where('sc.deleted_at', null)
                ->where('sc.sucursal_id', $request->sucursal_id)

                ->where('f.deleted_at', null)

                ->where('df.hist', 1)
                ->where('df.gestion', $gestion+1)
                ->where('df.deleted_at', null)                    

                ->select('p.id', 'p.nombre', DB::raw("SUM(df.cantrestante * df.precio) as r_finalBs"), DB::raw("SUM(df.cantrestante) as r_finalC"))
                // ->groupBy('p.id')
                ->get();

        // return $restante;

   




// esta fi esta funcionando
            $data = DB::table('solicitud_compras as sc')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'a.id', 'df.article_id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')
                        // ->leftJoin('detalle_egresos as de', 'de.detallefactura_id', 'df.id')
                        ->where('sc.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('df.deleted_at', null)
                        ->where('df.hist', 0)
                        ->where('sc.sucursal_id', $request->sucursal_id)
                        
                        // ->where('de.deleted_at', null)
                        ->select('p.id', 'p.codigo', 'p.nombre',DB::raw("SUM(df.cantsolicitada) as cantidadinicial"), DB::raw("SUM(df.cantsolicitada * df.precio) as totalinicial"),
                                DB::raw("SUM(df.cantrestante) as cantfinal"), DB::raw("SUM((df.cantrestante) * df.precio) as totalfinal")
                                )
                        ->groupBy('p.id')
                        ->get();
        
        
        if($request->print==1)
        {
            return view('almacenes/report/inventarioAnual/partidaGeneral/print', compact('data', 'gestion', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new AnualPartidaExport($data, $gestion), $sucursal->nombre.' - Partida Anual '.$gestion.'.xlsx');
        }
        if($request->print ==NULL)
        {            
            return view('almacenes/report/inventarioAnual/partidaGeneral/list', compact('data'));
        }
    }
    // ################################################################################

    //para el inventario anual Detallado por ITEM
    public function inventarioDetalle()
    {

        $gestion = InventarioAlmacen::where('deleted_at', null)->where('status', 0)->get();

        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
                        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }
                
        $sucursal = SucursalUser::where('condicion', 1)
                ->where('deleted_at', null)
                ->whereRaw($query_filter)
                ->GroupBy('sucursal_id')
                ->get();        

        return view('almacenes/report/inventarioAnual/detalleGeneral/report', compact('sucursal', 'gestion'));
    }

    public function inventarioDetalleList(Request $request)
    {
        $gestion = $request->gestion;

        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        
                $data = DB::table('solicitud_compras as sc')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'a.id', 'df.article_id')

                        ->where('sc.gestion', $gestion)
                        ->where('sc.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('df.deleted_at', null)
                        ->where('df.hist', 0)

                        ->where('sc.sucursal_id', $request->sucursal_id)
                        ->select('a.id', 'a.presentacion', 'a.nombre', 'df.precio',
                                DB::raw("SUM(df.cantsolicitada) as cEntrada"), DB::raw("SUM(df.cantsolicitada - df.cantrestante) as cSalida"), DB::raw("SUM(df.cantrestante) as cFinal"),

                                DB::raw("SUM(df.totalbs) as vEntrada"), DB::raw("SUM((df.cantsolicitada - df.cantrestante) * df.precio) as vSalida"), DB::raw("SUM(df.cantrestante * df.precio) as vFinal")
                                // ,DB::raw("SUM(de.cantsolicitada) as cantidadFinal"), DB::raw("SUM(de.totalbs) as totalFinal")
                                )
                        // ->groupBy('a.id')
                        ->groupBy('a.id')
                        ->groupBy('df.precio')
                        ->get();


                foreach($data as $item)
                {
                    $item->cInicial=0.0;
                    $item->vInicial=0.0;
                    // $aux = $aux + $item->vFinal;
                }
       

        if($request->print==1){
            return view('almacenes/report/inventarioAnual/detalleGeneral/print', compact('data', 'gestion', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new AnualDetalleExport($data, $gestion), $sucursal->nombre.' - Detalle Anual '.$gestion.'.xlsx');
        }

        if($request->print==NULL)
        {            
            return view('almacenes/report/inventarioAnual/detalleGeneral/list', compact('data'));
        }
    }


    // #####################################################################################################################################################################################################################
    // ################################                 ARTCLE                 ###########################################################################################################
    // #####################################################################################################################################################################################################################
    //para ver el stock de articulo disponible en el almacen
    public function articleStock()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();
                        
        $direction = $this->getDirecciones();        

        return view('almacenes/report/article/stock/report', compact('sucursal', 'direction'));
    }

    public function articleStockList(Request $request)
    {
        
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);
        // dd($request);
        // dd($sucursal);
        // $start = $request->start;
        // $finish = $request->finish;
        $data = DB::table('solicitud_compras as sc')
                    ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->join('articles as a', 'a.id', 'df.article_id')
                    ->join('modalities as m', 'm.id', 'sc.modality_id')
                    ->join('providers as p', 'p.id', 'f.provider_id')

                    ->where('sc.deleted_at', null)
                    ->where('sc.sucursal_id', $request->sucursal_id)

                    ->where('f.deleted_at', null)


                    ->where('df.cantrestante', '>', 0)
                    ->where('df.hist', 0)
                    ->where('df.deleted_at', null)

                    ->select('df.fechaingreso', 'm.nombre as modalidad', 'sc.nrosolicitud', 'p.razonsocial as proveedor',
                            'f.tipofactura', 'f.nrofactura', 'a.id as article_id', 'a.nombre as articulo', 'a.presentacion', 'df.cantsolicitada', 'df.precio',
                            'df.cantrestante', 'df.totalbs', 'sc.id')
                    ->orderBy('df.fechaingreso', 'ASC')
                    ->orderBy('sc.id', 'ASC')
                    ->get();

      
        if($request->print==1){
            return view('almacenes.report.article.stock.print', compact('data', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new ArticleStockExport($data), $sucursal->nombre.'_'.$date.'.xlsx');
        }
        if($request->print==NULL)
        {            
            return view('almacenes.report.article.stock.list', compact('data'));
        }
    }
    // _________________________________________________
    public function articleList()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();
                         

        return view('almacenes.report.article.list.report', compact('sucursal'));
    }

    public function articleListList(Request $request)
    {
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        $data = DB::table('partidas as p')
                    ->join('articles as a', 'a.partida_id', 'p.id')
                    ->where('a.condicion', 1)
                    ->where('a.deleted_at', null)
                    ->select('p.nombre as partida', 'p.codigo', 'a.nombre', 'a.presentacion')
                    ->orderBy('a.nombre', 'ASC')
                    ->get();
        // dd($data);

        if($request->print==1){
            return view('almacenes.report.article.list.print', compact('data', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new ArticleListExport($data), $sucursal->nombre.'-Lista de articulos'.'_'.$date.'.xlsx');
        }
        if($request->print==NULL)
        {            
            return view('almacenes.report.article.list.list', compact('data'));
        }
    }
    
    //  ___________________________________________

    public function incomeOffice()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();    

        return view('almacenes.report.article.incomeOffice.report', compact('sucursal'));
    }

    public function incomeOfficeList(Request $request)
    {
        // dd($request);
        $finish = $request->finish;
        $start = $request->start;
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);
        $message = '';

        if($request->unidad_id == 'TODO')
        {
            $message = 'Dirección Administrativa - '.$this->getDireccion($request->direccion_id)->nombre;
            $data = DB::table('solicitud_compras as cp')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'cp.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'df.article_id', 'a.id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')
                        ->where('df.deleted_at', null)
                        ->where('df.hist', 0)
                        ->where('f.deleted_at', null)
                        ->where('cp.deleted_at', null)
                        ->where('cp.direccionadministrativa', $request->direccion_id)
                        ->where('cp.fechaingreso', '>=', $request->start)
                        ->where('cp.fechaingreso', '<=', $request->finish)

                        ->where('cp.sucursal_id', $request->sucursal_id)

                        ->select('cp.unidadadministrativa as unidad','cp.fechaingreso',  'a.nombre as articulo', 'p.nombre as partida', 'nrosolicitud', 'a.presentacion', 'df.precio', 'df.cantsolicitada', 'df.totalbs')
                        // ->orderBy('u.id')
                        ->orderBy('cp.fechaingreso')
                        ->get();
            foreach($data as $item)
            {
                $item->unidad = Unit::find($item->unidad)->nombre;
            }


           

            // $datas = DB::connection('mamore')->table('unidades as u')
            //             ->join('sysalmacen.solicitud_compras as cp', 'cp.unidadadministrativa', 'u.id')
            //             ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'cp.id')
            //             ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
            //             ->join('sysalmacen.articles as a', 'df.article_id', 'a.id')
            //             ->join('sysalmacen.partidas as p', 'p.id', 'a.partida_id')
            //             ->where('df.deleted_at', null)
            //             ->where('df.hist', 0)
            //             ->where('f.deleted_at', null)
            //             ->where('cp.deleted_at', null)
            //             ->where('u.direccion_id', $request->direccion_id)
            //             ->where('cp.fechaingreso', '>=', $request->start)
            //             ->where('cp.fechaingreso', '<=', $request->finish)

            //             ->where('cp.sucursal_id', $request->sucursal_id)

            //             ->select('u.nombre as unidad','cp.fechaingreso',  'a.nombre as articulo', 'p.nombre as partida', 'nrosolicitud', 'a.presentacion', 'df.precio', 'df.cantsolicitada', 'df.totalbs')
            //             ->orderBy('u.id')
            //             ->orderBy('cp.fechaingreso')
            //             ->get();
        }
        else      
        {
            $message = 'Unidad - '.$this->getUnidad($request->unidad_id)->nombre;
            $data = DB::table('solicitud_compras as cp')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'cp.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'df.article_id', 'a.id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')
                        ->where('df.deleted_at', null)
                        ->where('df.hist', 0)
                        ->where('f.deleted_at', null)
                        ->where('cp.deleted_at', null)
                        ->where('cp.unidadadministrativa', $request->unidad_id)
                        ->where('cp.fechaingreso', '>=', $request->start)
                        ->where('cp.fechaingreso', '<=', $request->finish)
                        ->where('cp.sucursal_id', $request->sucursal_id)

                        ->select('cp.unidadadministrativa as unidad','cp.fechaingreso',  'a.nombre as articulo', 'p.nombre as partida', 'nrosolicitud', 'a.presentacion', 'df.precio', 'df.cantsolicitada', 'df.totalbs')
                        // ->orderBy('u.id')
                        ->orderBy('cp.fechaingreso')
                        ->get();

            foreach($data as $item)
            {
                $item->unidad = Unit::find($item->unidad)->nombre;
            }
        }
        if($request->print==1){
            return view('almacenes.report.article.incomeOffice.print', compact('data', 'sucursal',  'message', 'finish', 'start'));
        }
        if($request->print==2)
        {
            return Excel::download(new ArticleIncomeOfficeExport($data), $sucursal->nombre.' - Ingreso Artículo '.'_'.$date.'.xlsx');
        }
        if($request->print==NULL)
        {            
            return view('almacenes.report.article.incomeOffice.list', compact('data'));
        }
    }

    public function ajax_incomeOffice_direccion($id)
    {
        return $this->direccionSucursal($id);
    }

    public function ajax_incomeOffice_unidad($id)
    {
        return $this->getUnidades($id);
    }

    // _________________________________________________________
    //  para los egresos por todas las oficina de una direcion o por oficinas.......
    public function egressOffice()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();    
        // return $sucursal;

        return view('almacenes.report.article.egressOffice.report', compact('sucursal'));
    }

    public function egressOfficeList(Request $request)
    {
        // dd($request->start);
        $finish = $request->finish;
        $start = $request->start;
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);
        $message = '';

        // $ok = SolicitudEgreso::all();

        // foreach($ok as $item)
        // {
        //     SolicitudEgreso::where('id',$item->id)->update(['direccionadministrativa'=>$this->getUnidad($item->unidadadministrativa)->direccion_id]);
        // }
        // return $ok;

        if($request->unidad_id == 'TODO')
        {
            $message = 'Dirección Administrativa - '.$this->getDireccion($request->direccion_id)->nombre;
            $data = DB::table('solicitud_egresos as se')
                        ->join('detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                        ->join('detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                        ->join('articles as a', 'df.article_id', 'a.id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')

                        ->where('se.deleted_at', null)
                        ->where('se.direccionadministrativa', $request->direccion_id)
                        ->where('se.fechaegreso', '>=', $request->start)
                        ->where('se.fechaegreso', '<=', $request->finish)
                        ->where('se.sucursal_id', $request->sucursal_id)

                        ->where('de.deleted_at', null)                        

                        ->select('se.unidadadministrativa as unidad', 'se.fechaegreso', 'a.nombre as articulo', 'p.nombre as partida', 'se.nropedido', 'a.presentacion', 'de.precio', 'de.cantsolicitada', 'de.totalbs')
                        ->orderBy('se.fechaegreso')
                        ->get();

            foreach($data as $item)
            {
                $item->unidad = Unit::find($item->unidad)->nombre;
            }
        }
        else      
        {
            $message = 'Unidad - '.$this->getUnidad($request->unidad_id)->nombre;
            $data = DB::table('solicitud_egresos as se')
                        ->join('detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                        ->join('detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                        ->join('articles as a', 'df.article_id', 'a.id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')


                        ->where('se.deleted_at', null)
                        ->where('se.unidadadministrativa', $request->unidad_id)

                        ->where('se.fechaegreso', '>=', $request->start)
                        ->where('se.fechaegreso', '<=', $request->finish)
                        ->where('se.sucursal_id', $request->sucursal_id)

                        ->where('de.deleted_at', null) 

                        ->select('se.unidadadministrativa as unidad', 'se.fechaegreso', 'a.nombre as articulo', 'p.nombre as partida', 'se.nropedido', 'a.presentacion', 'de.precio', 'de.cantsolicitada', 'de.totalbs')
                        ->orderBy('se.fechaegreso')
                        ->get();

            foreach($data as $item)
            {
                $item->unidad = Unit::find($item->unidad)->nombre;
            }
        }
        if($request->print==1){
            return view('almacenes.report.article.egressOffice.print', compact('data', 'sucursal', 'start', 'finish', 'message'));
        }
        if($request->print==2)
        {
            return Excel::download(new ArticleEgressOfficeExport($data), $sucursal->nombre.' - Egreso Artículo '.'_'.$date.'.xlsx');
        }
        if($request->print==NULL)
        {            
            return view('almacenes.report.article.egressOffice.list', compact('data'));
        }
    }




    // #######################################################################
    // #######################################################################
    // #######################################################################
    // para los proveedores
    public function provider()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();
                        
        $direction = $this->getDirecciones();        

        return view('almacenes.report.provider.list.report', compact('sucursal', 'direction'));
    }

    public function providerList(Request $request)
    {
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        $data = Provider::where('sucursal_id', $request->sucursal_id)->where('condicion', 1)->get();
        // dd($request);
 
        if($request->print==1){
            return view('almacenes.report.provider.list.print', compact('data', 'sucursal'));
        }
        if($request->print==2)
        {
            return Excel::download(new ProviderListExport($data), $sucursal->nombre.'_Lista Proveedores '.$date.'xlsx');
        }
        if($request->print==NULL)
        {            
            return view('almacenes.report.provider.list.list', compact('data'));
        }
    }





    //################################################              REPORTE ADITIONAL           ###################################

    // para los usuarios
    public function user()
    {
        $user = Auth::user();
        $query_filter = 'user_id ='.Auth::user()->id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = SucursalUser::where('condicion', 1)
                        ->where('deleted_at', null)
                        ->whereRaw($query_filter)
                        ->GroupBy('sucursal_id')
                        ->get();
                        
        $direction = $this->getDirecciones();        

        // return view('almacenes.report.aditional.list.report', compact('sucursal', 'direction'));
        return view('almacenes.report.aditional.user.report', compact('sucursal', 'direction'));

    }

    public function userList(Request $request)
    {
        $data = DB::table('sucursals as s')
                    ->leftJoin('solicitud_compras as sc', 's.id', 'sc.sucursal_id')
                    ->leftJoin('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->leftJoin('detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->leftJoin('articles as a', 'a.id', 'df.article_id')
                    

                    // ->where('su.condicion', 1)

                    ->where('sc.deleted_at', null)
                    ->where('f.deleted_at', null)
                    ->where('df.deleted_at', null)

                    // ->whereDate('sc.fechaingreso', '>=', date('Y-m-d', strtotime($request->start)))
                    // ->whereDate('sc.fechaingreso', '<=', date('Y-m-d', strtotime($request->finish)))
                    ->select('s.nombre as almacen', 's.id as sucursal_id',

                            // DB::raw("SUM(df.cantsolicitada) as cEntrada"), DB::raw("SUM(df.cantsolicitada - df.cantrestante) as cSalida"), DB::raw("SUM(df.cantrestante) as cFinal"),

                            // DB::raw("SUM(df.totalbs) as vEntrada"), DB::raw("SUM((df.cantsolicitada - df.cantrestante) * df.precio) as vSalida"), DB::raw("SUM(df.cantrestante * df.precio) as vFinal")
                            
                            DB::raw("SUM(df.cantsolicitada) as cEntrada"),

                            DB::raw("SUM(df.totalbs) as vEntrada")   
                            )
                    ->groupBy('s.id')
                    ->get();
        // dd($data);


        if($request->print){
            return view('almacenes.report.aditional.user.print', compact('data'));
        }
        else
        {            
            return view('almacenes.report.aditional.user.list', compact('data'));
        }
    }









































    // para los articlos en general SAldo inicial, entrada, salida, saldo final,.....expresado en boliviano y en cantidades
    public function articleInventory()
    {
        $user = Auth::user();
        $sucursal = SucursalUser::where('user_id', $user->id)->get();

        return view('almacenes/report/article/inventory/report', compact('sucursal'));
    }

    public function articleInventoryList(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;


        // $data = DB::table('solicitud_compras as sp')
        //             ->join('facturas as f', 'f.solicitudcompra_id', 'sp.id')
        //             ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
        //             ->join('article as a')

        if($request->print){
            return view('almacenes/report/article/inventory/print', compact('start', 'finish'));
        }else
        {            
            return view('almacenes/report/article/inventory/list');
        }
    }







    


    //REPORTES PARA OBTENER LAS UNIDADES QUE AN PARTICIPADO EN ESE ARTICULO
    public function articleUnidades()
    {
        $article = Article::where('deleted_at', null)->where('condicion', 1)->orderBy('nombre')->get();
        return view('almacenes/report/article/unidades/report', compact('article'));
    }
    
    public function articleUnidadesList(Request $request)
    {
        $type = $request->type;
        if($request->type == 1)
        {
            $data = DB::connection('mamore')->table('unidades as u')
                    ->join('sysalmacen.solicitud_compras as sc', 'sc.unidadadministrativa', 'u.id')
                    ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->join('sysalmacen.articles as a', 'a.id', 'df.article_id')
                    ->where('df.article_id', $request->article_id)
                    ->where('df.deleted_at', null)
                    ->where('f.deleted_at', null)
                    ->where('sc.deleted_at', null)
                    ->where('sc.fechaingreso', '>=', $request->start)
                    ->where('sc.fechaingreso', '<=', $request->finish)
                    ->select('u.nombre', 'sc.nrosolicitud')
                    ->orderBy('sc.fechaingreso')
                    ->get();
                    
        }
        else
        {
            $data = DB::connection('mamore')->table('unidades as u')
                    ->join('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                    ->join('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                    ->join('sysalmacen.detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                    ->join('sysalmacen.articles as a', 'a.id', 'df.article_id')
                    ->where('df.article_id', $request->article_id)
                    ->where('se.deleted_at', null)
                    ->where('de.deleted_at', null)
                    ->where('se.fechaegreso', '>=', $request->start)
                    ->where('se.fechaegreso', '<=', $request->finish)
                    ->select('*')
                    ->orderBy('se.fechaegreso')
                    ->get();

        }
        // dd($data);
        return view('almacenes/report/article/unidades/list', compact('data', 'type'));
    }
}