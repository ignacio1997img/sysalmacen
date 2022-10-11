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

class ReportAlmacenController extends Controller
{
    //para los reportes mediantes direciones admistrativa Income y Egress en Bolivianos  saldo
    public function directionIncomeSalida()
    {
        $user = Auth::user();
        $sucursal = SucursalUser::where('user_id', $user->id)->get();
        $direction = $this->getDireccion();        

        return view('almacenes/report/inventarioAnual/direccionAdministrativa/report', compact('sucursal', 'direction'));
    }
    public function directionIncomeSalidaList(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;

        $data = DB::connection('mamore')->table('direcciones as d')
                    ->leftJoin('sysalmacen.solicitud_compras as sc', 'sc.direccionadministrativa', 'd.id')
                    ->leftJoin('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->where('sc.deleted_at', null)
                    // ->where('d.direcciones_tipo_id', 1)
                    ->select('d.id', 'd.nombre',DB::raw("SUM(f.montofactura) as ingreso"))
                    ->groupBy('d.id')
                    ->get();

        $salida = DB::connection('mamore')->table('direcciones as d')
                    ->leftJoin('unidades as u', 'u.direccion_id', 'd.id')
                    ->leftJoin('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                    ->leftJoin('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                    ->where('se.deleted_at', null)
                    ->where('de.deleted_at', null)
                    // ->where('d.direcciones_tipo_id', 1)
                    ->select('d.id', 'd.nombre',DB::raw("SUM(de.totalbs) as egreso"))
                    ->groupBy('d.id')
                    ->get();
        
        foreach($data as $item)
        {
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
        if($request->print){
            return view('almacenes/report/inventarioAnual/direccionAdministrativa/print', compact('start', 'finish'));
        }else
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

        $direction = $this->getDireccion();        

        return view('almacenes/report/inventarioAnual/partidaGeneral/report', compact('sucursal', 'direction'));
    }

    public function inventarioPartidaList(Request $request)
    {
        // dd($request);
        $gestion = $request->gestion;

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
                        // ->where('de.deleted_at', null)
                        ->select('p.id', 'p.codigo', 'p.nombre',DB::raw("SUM(df.cantsolicitada) as cantidadinicial"), DB::raw("SUM(df.totalbs) as totalinicial"),
                                // ,DB::raw("SUM(de.cantsolicitada) as cantidadFinal"), DB::raw("SUM(de.totalbs) as totalFinal")
                                // DB::raw("SUM(df.cantsolicitada - df.cantrestante) as cantfinal"), DB::raw("SUM((df.cantsolicitada - df.cantrestante) * df.precio) as totalfinal")
                                DB::raw("SUM(df.cantrestante) as cantfinal"), DB::raw("SUM((df.cantrestante) * df.precio) as totalfinal")
                                )
                        ->groupBy('p.id')
                        ->get();
        }
        else
        {
            return 'para mas gestiones';
        }

      
    
        // $aux = DB::table('solicitud_compras as sc')
        //                 ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
        //                 ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
        //                 ->join('articles as a', 'a.id', 'df.article_id')
        //                 ->join('partidas as p', 'p.id', 'a.partida_id')
        //                 ->join('detalle_egresos as de', 'de.detallefactura_id', 'df.id')
        //                 ->where('sc.deleted_at', null)
        //                 ->where('f.deleted_at', null)
        //                 ->where('df.deleted_at', null)
        //                 ->where('de.deleted_at', null)
        //                 ->select('p.id', 'p.nombre', 
        //                     DB::raw("SUM(de.cantsolicitada) as cantidadFinal"), DB::raw("SUM(de.totalbs) as totalFinal"))
        //                 ->groupBy('p.id')
        //                 ->get();

        // foreach($data as $item)
        // {
        //     $item->cantfinal=0.0;
        //     $item->totalfinal="0.0";
        //     // if(!$item->ingreso)
        //     // {
        //     //     
        //     // }
        //     foreach($aux as $sal)
        //     {
        //         if($item->id == $sal->id)
        //         {
        //             $item->cantfinal = $item->cantfinal + $sal->cantidadFinal;
        //             $item->totalfinal = $item->totalfinal + $sal->totalFinal;
        //         }
        //     }
        // }


    





        // $data = DB::table('solicitud_compras as sc')
        //                 ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
        //                 ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
        //                 ->join('articles as a', 'a.id', 'df.article_id')
        //                 ->join('partidas as p', 'p.id', 'a.partida_id')
        //                 ->where('sc.deleted_at', null)
        //                 ->where('f.deleted_at', null)
        //                 ->where('df.deleted_at', null)
        //                 ->where('p.id', 31)
        //                 ->select('p.id', 'p.nombre',DB::raw("SUM(df.cantsolicitada) as cantidadinicial"), DB::raw("SUM(df.totalbs) as total"))
        //                 ->groupBy('p.id')
        //                 ->get();


        // dd($data);




        // $data = DB::connection('mamore')->table('direcciones as d')
        //             ->leftJoin('sysalmacen.solicitud_compras as sc', 'sc.direccionadministrativa', 'd.id')
        //             ->leftJoin('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
        //             ->where('sc.deleted_at', null)
        //             // ->where('d.direcciones_tipo_id', 1)
        //             ->select('d.id', 'd.nombre',DB::raw("SUM(f.montofactura) as ingreso"))
        //             ->groupBy('d.id')
        //             ->get();




        // $salida = DB::connection('mamore')->table('direcciones as d')
        //             ->leftJoin('unidades as u', 'u.direccion_id', 'd.id')
        //             ->leftJoin('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
        //             ->leftJoin('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
        //             ->where('se.deleted_at', null)
        //             // ->where('d.direcciones_tipo_id', 1)
        //             ->select('d.id', 'd.nombre',DB::raw("SUM(de.totalbs) as egreso"))
        //             ->groupBy('d.id')
        //             ->get();
        
        // foreach($data as $item)
        // {
        //     if(!$item->ingreso)
        //     {
        //         $item->ingreso="0.0";
        //     }
        //     foreach($salida as $sal)
        //     {
        //         if($item->id == $sal->id)
        //         {
        //             $item->salida = $sal->egreso;
        //         }
        //     }
        // }
        // foreach($data as $item)
        // {
        //     if(!$item->salida)
        //     {
        //         $item->salida="0.0";
        //     }
        // }
        if($request->print){
            return view('almacenes/report/inventarioAnual/partidaGeneral/print', compact('data', 'gestion'));
        }else
        {            
            return view('almacenes/report/inventarioAnual/partidaGeneral/list', compact('data'));
        }
    }

    // ################################################################################

    //para el inventario anual Detalldo por ITEM
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
        // dd($request);
        $gestion = $request->gestion;

        if($gestion == '2022')
        {
                $data = DB::table('solicitud_compras as sc')
                        ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                        ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                        ->join('articles as a', 'a.id', 'df.article_id')
                        ->where('sc.deleted_at', null)
                        ->where('f.deleted_at', null)
                        ->where('df.deleted_at', null)
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
                }
        }
        else 
        {
            return 'para mas gestiones';
        }



        if($request->print){
            return view('almacenes/report/inventarioAnual/detalleGeneral/print', compact('data', 'gestion'));
        }else
        {            
            return view('almacenes/report/inventarioAnual/detalleGeneral/list', compact('data'));
        }
    }


    //################################################################################3
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
                        
        $direction = $this->getDireccion();        

        return view('almacenes/report/article/stock/report', compact('sucursal', 'direction'));
    }

    public function articleStockList(Request $request)
    {
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
        if($request->print){
            return view('almacenes/report/article/stock/print', compact('data'));
        }else
        {            
            return view('almacenes/report/article/stock/list', compact('data'));
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