<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SucursalUnidadPrincipal;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudCompra;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\User;

class ExistingProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $user = Auth::user();
        
        $query_filter = 'id ='.$user->sucursal_id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter = 1;
        }

        $sucursal = Sucursal::whereRaw($query_filter)->get();  

        return view('almacenes.existingProduct.report', compact('sucursal'));
        
    }

    public function articleExistingList(Request $request)
    {
        // dump($request);
        $user = Auth::user();

        $sucursal = Sucursal::find($request->sucursal_id);

        $query_type = 1;
        if($request->type_id != 'TODO')
        {
            $query_type = 'sc.subSucursal_id = '. $request->type_id;
        }


        $query = 1;

        if(!Auth::user()->hasRole('admin'))
        {
            $mainUnit = SucursalUnidadPrincipal::where('sucursal_id', $user->sucursal_id)->where('status', 1)->where('deleted_at', null)->get();
        
            $query = '(sc.unidadadministrativa = '.$user->unidadAdministrativa_id.')';
            if(count($mainUnit)== 1)
            {
                $query = '(sc.unidadadministrativa = '.$user->unidadAdministrativa_id.' or sc.unidadadministrativa = '.$mainUnit[0]->unidadAdministrativa_id.')';
            }

            if(count($mainUnit)== 2)
            {
                $query = '(sc.unidadadministrativa = '.$user->unidadAdministrativa_id.' or sc.unidadadministrativa = '.$mainUnit[0]->unidadAdministrativa_id.' or sc.unidadadministrativa = '.$mainUnit[1]->unidadAdministrativa_id.')';
            }
        }
        // dump($query);

        
        $data = DB::table('solicitud_compras as sc')
                    ->join('facturas as f', 'f.solicitudcompra_id', 'sc.id')
                    ->join('detalle_facturas as df', 'df.factura_id', 'f.id')
                    ->join('articles as a', 'a.id', 'df.article_id')
                    ->join('partidas as pa', 'pa.id', 'a.partida_id')
                    ->join('modalities as m', 'm.id', 'sc.modality_id')
                    ->join('providers as p', 'p.id', 'f.provider_id')

                    ->where('sc.deleted_at', null)
                    ->where('sc.sucursal_id', $request->sucursal_id)
                    ->whereRaw($query_type)
                    ->whereRaw($query)

                    ->where('f.deleted_at', null)


                    ->where('df.cantrestante', '>', 0)
                    ->where('df.hist', 0)
                    ->where('df.deleted_at', null)

                    ->select('pa.codigo', 'pa.nombre as partida', 'a.id as article_id', 'a.nombre as articulo', 'a.presentacion')
                    ->orderBy('articulo', 'ASC')
                    ->groupBy('article_id')
                    ->get();
        // dump($data);
      
    
        if($request->print==NULL)
        {            
            return view('almacenes.existingProduct.list', compact('data'));
        }
    }
}
