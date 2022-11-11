<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SolicitudCompra;
use App\Models\Article;
use App\Models\Factura;
use App\Models\DetalleFactura;
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

class ReportAlmacenController extends Controller
{
    //para los reportes mediantes direciones admistrativa Income y Egress en Bolivianos  saldo
    public function directionIncomeSalida()
    {

//         select sum(cantsolicitada * precio), totalbs from detalle_facturas where deleted_at is null
            // GROUP BY id, precio
        // $data = DB::table('detalle_facturas as d')
        // ->where('d.deleted_at', null)
        // ->select('d.id', DB::raw("SUM(d.cantsolicitada * d.precio) as ingreso"), 'd.totalbs')
        // ->groupBy('d.id')
        // ->get();
        // foreach($data as $item)
        // {
        //     if($item->ingreso != $item->totalbs)
        //     {
        //         DetalleFactura::where('id', $item->id)->update(['totalbs'=>$item->ingreso]);
        //     }
        // }

        // dd($data);

        // foreach($data as $item)
        // {
        //     if($item->ingreso != $item->totalbs && $item->id !=585)
        //     {
        //         dd($item->id);
        //     }
        // }


//         SELECT f.id, f.montofactura, SUM(df.totalbs) FROM facturas as f inner join detalle_facturas as df on df.factura_id = f.id
// where df.deleted_at is null
// GROUP BY df.factura_id;

            // $datas = DB::table('facturas as f')
            //     ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
            //     ->where('f.deleted_at', null)
            //     ->where('df.deleted_at', null)
            //     ->select('f.id', 'f.montofactura', DB::raw("SUM(df.totalbs) as totalbs"))
            //     ->groupBy('df.factura_id')
            //     ->get();

            // foreach($datas as $item)
            // {
            //     if($item->montofactura != $item->totalbs)
            //     {
            //         Factura::where('id',$item->id)->update(['montofactura'=>$item->totalbs]);
            //     }
            // }

            











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

        return view('almacenes/report/inventarioAnual/direccionAdministrativa/report', compact('sucursal', 'direction'));
    }
    public function directionIncomeSalidaList(Request $request)
    {
        // dd($request);
        $gestion = $request->gestion;

        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);
        if($request->gestion == '2022')
        {
            $data = DB::connection('mamore')->table('direcciones as d')
                    ->join('sysalmacen.sucursal_direccions as s', 's.direccionAdministrativa_id', 'd.id')
                    ->leftJoin('sysalmacen.solicitud_compras as sc', 'sc.direccionadministrativa', 'd.id')
                    ->leftJoin('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')       
                    // ->leftJoin('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')             
                    ->where('sc.deleted_at', null)
                    ->where('f.deleted_at', null)
                    // ->where('df.deleted_at', null)
                    ->where('s.deleted_at', null)
                    ->where('s.status', 1)
                    ->where('s.sucursal_id', $request->sucursal_id)

                    ->select('d.id', 'd.nombre',DB::raw("SUM(f.montofactura) as ingreso"))
                    ->groupBy('d.id')
                    ->get();


            // $data = DB::connection('mamore')->table('direcciones as d')
            //             ->leftJoin('sysalmacen.solicitud_compras as sc', 'sc.direccionadministrativa', 'd.id')
            //             ->leftJoin('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
            //             ->where('sc.deleted_at', null)
            //             // ->where('d.direcciones_tipo_id', 1)
            //             ->select('d.id', 'd.nombre',DB::raw("SUM(f.montofactura) as ingreso"))
            //             ->groupBy('d.id')
            //             ->get();

            $salida = DB::connection('mamore')->table('direcciones as d')
                        ->leftJoin('unidades as u', 'u.direccion_id', 'd.id')
                        ->leftJoin('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                        ->leftJoin('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                        ->where('se.deleted_at', null)
                        ->where('de.deleted_at', null)
                        // ->where('d.direcciones_tipo_id', 1)
                        ->select('d.id',DB::raw("SUM(de.totalbs) as egreso"))
                        // ->groupBy('u.id')
                        ->groupBy('d.id')
                        ->get();
            // dd($data);
            
            foreach($data as $item)
            {
                $item->inicio=0;
                if(!$item->ingreso)
                {
                    $item->ingreso="0.0";
                }
                foreach($salida as $sal)
                {
                    if($item->id == $sal->id)
                    {
                        $item->salida = $sal->egreso;
                    }
                }
            }
            foreach($data as $item)
            {
                if(!$item->salida)
                {
                    $item->salida="0.0";
                }
            }
            // dd($data);
        }

       

        if($request->print==1)
        {
            return view('almacenes/report/inventarioAnual/direccionAdministrativa/print', compact('data', 'gestion'));
        }
        if($request->print==2)
        {
            return Excel::download(new AnualDaExport($data), $sucursal->nombre.' - DA Anual '.$gestion.'.xlsx');
        }
        if($request->print ==NULL)
        {            
            return view('almacenes/report/inventarioAnual/direccionAdministrativa/list', compact('data'));
        }
    }

    // ################################################################################
    // para ver el inventario por partida anual
    public function inventarioPartida()
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

        // $direction = $this->getDireccion();        

        return view('almacenes/report/inventarioAnual/partidaGeneral/report', compact('sucursal'));
    }

    public function inventarioPartidaList(Request $request)
    {
        // dd($request);
        $gestion = $request->gestion;

        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        if($gestion == '2022')
        {
            $data = DB::table('solicitud_compras as sc')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'a.id', 'df.article_id')
                        ->join('partidas as p', 'p.id', 'a.partida_id')
                        // ->leftJoin('detalle_egresos as de', 'de.detallefactura_id', 'df.id')
                        ->where('sc.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('df.deleted_at', null)
                        ->where('sc.sucursal_id', $request->sucursal_id)
                        
                        // ->where('de.deleted_at', null)
                        ->select('p.id', 'p.codigo', 'p.nombre',DB::raw("SUM(df.cantsolicitada) as cantidadinicial"), DB::raw("SUM(df.totalbs) as totalinicial"),
                                DB::raw("SUM(df.cantrestante) as cantfinal"), DB::raw("SUM((df.cantrestante) * df.precio) as totalfinal")
                                )
                        ->groupBy('p.id')
                        ->get();
        }
        else
        {
            return 'para mas gestiones';
        }
        if($request->print==1)
        {
            return view('almacenes/report/inventarioAnual/partidaGeneral/print', compact('data', 'gestion'));
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
        // $sucursal = SucursalUser::where('user_id', $user->id)->get();
        // $direction = $this->getDireccion();        

        return view('almacenes/report/inventarioAnual/detalleGeneral/report', compact('sucursal'));
    }

    public function inventarioDetalleList(Request $request)
    {
        $gestion = $request->gestion;

        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);

        if($gestion == '2022')
        {
                $data = DB::table('solicitud_compras as sc')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'a.id', 'df.article_id')
                        ->where('sc.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('df.deleted_at', null)
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
                        // $aux=0;

                foreach($data as $item)
                {
                    $item->cInicial=0.0;
                    $item->vInicial=0.0;
                    // $aux = $aux + $item->vFinal;
                }
        }
        else 
        {
            return 'para mas gestiones';
        }
        // dd($aux);


        if($request->print==1){
            return view('almacenes/report/inventarioAnual/detalleGeneral/print', compact('data', 'gestion'));
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
        // $start = $request->start;
        // $finish = $request->finish;
        $data = DB::connection('mamore')->table('unidades as u')
                    ->join('sysalmacen.solicitud_compras as sc', 'sc.unidadadministrativa', 'u.id')
                    ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->join('sysalmacen.articles as a', 'a.id', 'df.article_id')
                    ->join('sysalmacen.modalities as m', 'm.id', 'sc.modality_id')
                    ->join('sysalmacen.providers as p', 'p.id', 'f.provider_id')
                    ->where('df.cantrestante', '>', 0)
                    // ->where('df.fechaingreso', '>=', $request->start)
                    // ->where('df.fechaingreso', '<=', $request->finish)
                    ->where('sc.sucursal_id', $request->sucursal_id)

                    ->where('df.deleted_at', null)
                    ->where('f.deleted_at', null)
                    ->where('sc.deleted_at', null)
                    ->select('df.fechaingreso', 'u.nombre as unidad', 'm.nombre as modalidad', 'u.sigla', 'sc.nrosolicitud', 'p.razonsocial as proveedor',
                            'f.tipofactura', 'f.nrofactura', 'a.id as article_id', 'a.nombre as articulo', 'a.presentacion', 'df.cantsolicitada', 'df.precio',
                            'df.cantrestante', 'df.totalbs', 'sc.id')
                    ->orderBy('df.fechaingreso', 'ASC')
                    ->orderBy('sc.id', 'ASC')
                    ->get();

                    
        // return view('almacenes/report/article/unidades/list', compact('data', 'type'));

        // $data = DB::table('detalle_facturas as df')
        //         ->join('facturas as f', 'f.id', 'df.factura_id')
        //         ->join('solicitud_compras as sc', 'sc.id', 'f.solicitudcompra_id')
        //         ->join('articles as a', 'a.id', 'df.article_id')
        //         ->join('modalities as m', 'm.id', 'sc.modality_id')
        //         ->where('df.cantrestante', '>', 0)
        //         ->where('df.fechaingreso', '>=', $request->start)
        //         ->where('df.fechaingreso', '<=', $request->finish)
        //         ->where('df.deleted_at', null)
        //         ->where('f.deleted_at', null)
        //         ->where('sc.deleted_at', null)
        //         ->select('*')
        //         ->get();
        // dd($data);
        if($request->print==1){
            return view('almacenes.report.article.stock.print', compact('data'));
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
        // dd($request->start);
        $finish = $request->finish;
        $start = $request->start;
        $date = Carbon::now();
        $sucursal = Sucursal::find($request->sucursal_id);
        $message = '';

        if($request->unidad_id == 'TODO')
        {
            $message = 'Dirección Administrativa - '.$this->getDireccion($request->direccion_id)->nombre;
            $data = DB::connection('mamore')->table('unidades as u')
                        ->join('sysalmacen.solicitud_compras as cp', 'cp.unidadadministrativa', 'u.id')
                        ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'cp.id')
                        ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('sysalmacen.articles as a', 'df.article_id', 'a.id')
                        ->join('sysalmacen.partidas as p', 'p.id', 'a.partida_id')
                        ->where('df.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('cp.deleted_at', null)
                        ->where('u.direccion_id', $request->direccion_id)
                        ->where('cp.fechaingreso', '>=', $request->start)
                        ->where('cp.fechaingreso', '<=', $request->finish)

                        ->where('cp.sucursal_id', $request->sucursal_id)

                        ->select('u.nombre as unidad','cp.fechaingreso',  'a.nombre as articulo', 'p.nombre as partida', 'nrosolicitud', 'a.presentacion', 'df.precio', 'df.cantsolicitada', 'df.totalbs')
                        ->orderBy('u.id')
                        ->orderBy('cp.fechaingreso')
                        ->get();
        }
        else      
        {
            $message = 'Unidad - '.$this->getUnidad($request->unidad_id)->nombre;
            $data = DB::connection('mamore')->table('unidades as u')
                        ->join('sysalmacen.solicitud_compras as cp', 'cp.unidadadministrativa', 'u.id')
                        ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'cp.id')
                        ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('sysalmacen.articles as a', 'df.article_id', 'a.id')
                        ->join('sysalmacen.partidas as p', 'p.id', 'a.partida_id')
                        ->where('df.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('cp.deleted_at', null)
                        ->where('u.id', $request->unidad_id)
                        ->where('cp.fechaingreso', '>=', $request->start)
                        ->where('cp.fechaingreso', '<=', $request->finish)
                        ->where('cp.sucursal_id', $request->sucursal_id)

                        ->select('u.nombre as unidad','cp.fechaingreso',  'a.nombre as articulo', 'p.nombre as partida', 'nrosolicitud', 'a.presentacion', 'df.precio', 'df.cantsolicitada', 'df.totalbs')
                        ->orderBy('u.id')
                        ->orderBy('cp.fechaingreso')
                        ->get();
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

        if($request->unidad_id == 'TODO')
        {
            $message = 'Dirección Administrativa - '.$this->getDireccion($request->direccion_id)->nombre;
            $data = DB::connection('mamore')->table('unidades as u')
                        ->join('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                        ->join('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                        ->join('sysalmacen.detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                        ->join('sysalmacen.articles as a', 'df.article_id', 'a.id')
                        ->join('sysalmacen.partidas as p', 'p.id', 'a.partida_id')
                        ->where('se.deleted_at', null)
                        ->where('de.deleted_at', null)
                        ->where('u.direccion_id', $request->direccion_id)
                        ->where('se.fechaegreso', '>=', $request->start)
                        ->where('se.fechaegreso', '<=', $request->finish)

                        ->where('se.sucursal_id', $request->sucursal_id)

                        ->select('u.nombre as unidad','se.fechaegreso', 'a.nombre as articulo', 'p.nombre as partida', 'se.nropedido', 'a.presentacion', 'de.precio', 'de.cantsolicitada', 'de.totalbs')
                        ->orderBy('u.id')
                        ->orderBy('se.fechaegreso')
                        ->get();
        }
        else      
        {
            $message = 'Unidad - '.$this->getUnidad($request->unidad_id)->nombre;
            $data = DB::connection('mamore')->table('unidades as u')
                        ->join('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                        ->join('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                        ->join('sysalmacen.detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                        ->join('sysalmacen.articles as a', 'df.article_id', 'a.id')
                        ->join('sysalmacen.partidas as p', 'p.id', 'a.partida_id')
                        ->where('se.deleted_at', null)
                        ->where('de.deleted_at', null)
                        ->where('u.id', $request->unidad_id)
                        ->where('se.fechaegreso', '>=', $request->start)
                        ->where('se.fechaegreso', '<=', $request->finish)

                        ->where('se.sucursal_id', $request->sucursal_id)

                        ->select('u.nombre as unidad','se.fechaegreso', 'a.nombre as articulo', 'p.nombre as partida', 'se.nropedido', 'a.presentacion', 'de.precio', 'de.cantsolicitada', 'de.totalbs')
                        ->orderBy('u.id')
                        ->orderBy('se.fechaegreso')
                        ->get();
                        // dd($message);
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