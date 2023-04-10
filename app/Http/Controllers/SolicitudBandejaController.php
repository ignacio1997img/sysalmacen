<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\SolicitudCompra;
use App\Models\SolicitudPedido;
use App\Models\Factura;
use App\Models\Sucursal;
use App\Models\SucursalDireccion;
use App\Models\SucursalUser;
use App\Models\InventarioAlmacen;


class SolicitudBandejaController extends Controller
{
    public function index()
    {
        // return 1;
        return view('almacenes.inbox.browse');
    }

    public function list($type, $search = null){
        $user = Auth::user();

        $sucursal = SucursalUser::where('user_id', $user->id)->where('condicion', 1)->where('deleted_at', null)->first();
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
        
        // dump($gestion);

        $query_filter = 'people_id = '.$user->funcionario_id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }
        
        $paginate = request('paginate') ?? 10;

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
            ->where('status', '!=', 'Pendiente')
            ->whereRaw($query_filter)
            ->orderBy('id', 'DESC')->paginate($paginate);

        return view('almacenes.inbox.list', compact('data', 'gestion'));
    }


    // para rechazar las solicituded 
    public function rechazarSolicitud(Request $request)
    {
        SolicitudPedido::where('id', $request->id)->update(['status'=>'Rechazado', 'rechazadoUser_id'=>Auth::user()->id, 'rechazadoDate'=>Carbon::now()]);
        return redirect()->route('inbox.index')->with(['message' => 'Solicitud rechazada exitosamente.', 'alert-type' => 'success']);
    }
    
    // Para aprobar las solicitudes
    public function aprobarSolicitud(Request $request)
    {
        // return $request;
        SolicitudPedido::where('id', $request->id)->update(['status'=>'Aprobado', 'aprobadoUser_id'=>Auth::user()->id, 'aprobadoDate'=>Carbon::now()]);
        return redirect()->route('inbox.index')->with(['message' => 'Solicitud aprobada exitosamente.', 'alert-type' => 'success']);
    }


    public function show($id)
    {
        $data = SolicitudPedido::with('solicitudDetalle', 'sucursal')
            ->where('deleted_at', null)
            ->where('id', $id)
            ->first();
        SolicitudPedido::where('id', $id)->update(['visto'=>Carbon::now()]);
        return view('almacenes.inbox.read', compact('data'));
    }



}
