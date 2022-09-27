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
                    ->where('d.direcciones_tipo_id', 1)
                    ->select('d.id', 'd.nombre',DB::raw("SUM(f.montofactura) as ingreso"))
                    ->groupBy('d.id')
                    ->get();

        $salida = DB::connection('mamore')->table('direcciones as d')
                    ->leftJoin('unidades as u', 'u.direccion_id', 'd.id')
                    ->leftJoin('sysalmacen.solicitud_egresos as se', 'se.unidadadministrativa', 'u.id')
                    ->leftJoin('sysalmacen.detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                    ->where('se.deleted_at', null)
                    ->where('d.direcciones_tipo_id', 1)
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

    // para ver el inventario por partida anual
    public function inventarioPartida()
    {
        $user = Auth::user();
        $sucursal = SucursalUser::where('user_id', $user->id)->get();
        $direction = $this->getDireccion();        

        return view('almacenes/report/inventarioAnual/partidaGeneral/report', compact('sucursal', 'direction'));
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







    //para ver el stock de articulo disponible en el almacen
    public function articleStock()
    {
        return view('almacenes/report/article/stock/report');
    }

    public function articleStockList(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;
        $data = DB::connection('mamore')->table('unidades as u')
                    ->join('sysalmacen.solicitud_compras as sc', 'sc.unidadadministrativa', 'u.id')
                    ->join('sysalmacen.facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->join('sysalmacen.detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->join('sysalmacen.articles as a', 'a.id', 'df.article_id')
                    ->join('sysalmacen.modalities as m', 'm.id', 'sc.modality_id')
                    ->join('sysalmacen.providers as p', 'p.id', 'f.provider_id')
                    ->where('df.cantrestante', '>', 0)
                    ->where('df.fechaingreso', '>=', $request->start)
                    ->where('df.fechaingreso', '<=', $request->finish)
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
            return view('almacenes/report/article/stock/print', compact('data', 'start', 'finish'));
        }else
        {            
            return view('almacenes/report/article/stock/list', compact('data'));
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