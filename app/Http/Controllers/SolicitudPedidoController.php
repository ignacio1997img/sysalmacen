<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Sucursal;
use App\Models\SucursalUser;
use Illuminate\Http\Request;
use App\Models\SolicitudPedido;
use App\Models\InventarioAlmacen;
use App\Models\SucursalDireccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudPedidoDetalle;
use App\Models\SucursalUnidadPrincipal;
use App\Models\DetalleEgreso;
use App\Models\Factura;
use App\Models\SolicitudEgreso;
use App\Models\DetalleFactura;
use App\Models\SolicitudCompra;

use function PHPUnit\Framework\returnSelf;

class SolicitudPedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // return 1;
        return view('almacenes.outbox.browse');
    }
    public function list(){

        $search = request('search') ?? null;
        $type = request('type') ?? null;
        $paginate = request('paginate') ?? 10;

        // return $type; 

        $user = Auth::user();
        
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $user->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
        


        $query_filter = 'people_id = '.$user->funcionario_id;
        
        if(Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }        
        
        switch($type)
        {
            case 'eliminado':
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
                    ->where('status', 'eliminado')
                    ->whereRaw($query_filter)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    break;
            case 'entregado':
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
                    ->whereRaw("(status = 'Entregado' or status = 'pendienteeliminacion') and ".$query_filter)
                    // ->whereRaw($query_filter)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    break;
            case 'rechazado':
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
                    ->where('status', 'Rechazado')
                    ->whereRaw($query_filter)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    break;
            case 'pendiente':
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
                    ->whereRaw("(status = 'Pendiente' or status = 'Enviado' or status = 'Aprobado') and ".$query_filter)
                    // ->whereRaw($query_filter)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    break;
            
        }

        // $data =  SolicitudPedido::with(['solicitudDetalle'])
        //     ->where(function($query) use ($search){
        //         if($search){
        //             $query->OrWhereRaw($search ? "gestion like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "nropedido like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "id like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "unidad_name like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "direccion_name like '%$search%'" : 1);
        //         }
        //     })
        //     ->where('deleted_at', NULL)
        //     ->whereRaw($query_filter)
        //     ->orderBy('id', 'DESC')->paginate($paginate);

        return view('almacenes.outbox.list', compact('data', 'gestion'));
    }

    public function create()
    {
        $user = Auth::user();

        // $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        $sucursal = Sucursal::where('id', $user->sucursal_id)->first();
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $user->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada

        $funcionario = $this->getWorker($user->funcionario_id);
        // dd($funcionario);
        // return $user;

        $mainUnit = SucursalUnidadPrincipal::where('sucursal_id', $user->sucursal_id)->where('status', 1)->where('deleted_at', null)->first();
        // return $mainUnit;
        $query = '';
        if($mainUnit)
        {
            $query = ' or s.unidadadministrativa = '.$mainUnit->unidadAdministrativa_id;
        }
        $unidad = 'null';
        if($user->unidadAdministrativa_id)
        {
            // $unidad = $funcionario->id_unidad;
            $unidad = $user->unidadAdministrativa_id;
        }

        $data = DB::table('solicitud_compras as s')
                ->join('facturas as f', 'f.solicitudcompra_id', 's.id')
                ->join('detalle_facturas as d', 'd.factura_id', 'f.id')
                ->join('articles as a', 'a.id', 'd.article_id')

                ->where('s.sucursal_id', $user->sucursal_id)
                ->where('s.stock', 1)
                ->where('s.deleted_at', null)          
                // ->whereRaw('(s.unidadadministrativa = '.$funcionario->id_unidad.' or s.unidadadministrativa = 0)')
                ->whereRaw('(s.unidadadministrativa = '.$unidad.''.$query.')')
                // ->whereRaw('(s.unidadadministrativa = '.$funcionario->id_unidad.')')
                ->where('f.deleted_at', null)
                ->where('d.deleted_at', null)
                ->where('d.cantrestante', '>', 0)
                ->where('d.condicion', 1)
                ->where('d.hist', 0)
                ->select('s.id as solicitud_id', 'f.id as factura_id', 'a.id as article_id', 'a.nombre as article')
                ->groupBy('article_id')
                ->orderBy('article')
                ->get();
        return view('almacenes.outbox.edit-add', compact('gestion', 'sucursal', 'funcionario', 'user'));
    }



    //Funcion ajax para obtener los articulos disponible en el almacen
    public function ajaxProductExists()
    {
        $q = request('q');
        $user = Auth::user();


        // $funcionario = $this->getWorker($user->funcionario_id);

        $mainUnit = SucursalUnidadPrincipal::where('sucursal_id', $user->sucursal_id)->where('status', 1)->where('deleted_at', null)->first();
        // return $mainUnit;
        $query = '';
        if($mainUnit)
        {
            $query = ' or s.unidadadministrativa = '.$mainUnit->unidadAdministrativa_id;
        }
        $unidad = 'null';
        if($user->unidadAdministrativa_id)
        {
            // $unidad = $funcionario->id_unidad;
            $unidad = $user->unidadAdministrativa_id;
        }


        $data = DB::table('solicitud_compras as s')
                ->join('facturas as f', 'f.solicitudcompra_id', 's.id')
                ->join('detalle_facturas as d', 'd.factura_id', 'f.id')
                ->join('articles as a', 'a.id', 'd.article_id')
                ->where('s.sucursal_id', $user->sucursal_id)
                ->where('s.stock', 1)
                ->where('s.deleted_at', null)      
                // ->whereRaw('(s.unidadadministrativa = '.$funcionario->id_unidad.' or s.unidadadministrativa = 0)')
                ->whereRaw('(s.unidadadministrativa = '.$unidad.''.$query.')')
                // ->whereRaw('(s.unidadadministrativa = '.$funcionario->id_unidad.')')
                ->where('f.deleted_at', null)
                ->where('d.deleted_at', null)
                ->where('d.cantrestante', '>', 0)
                ->where('d.condicion', 1)
                ->where('d.hist', 0)
                ->select('a.id', 'a.nombre as nombre', 'a.image', 'a.presentacion')
                ->whereRaw("(nombre like '%$q%')")
                ->groupBy('id')
                ->orderBy('nombre')
                ->get();

        
        // $data = IncomesDetail::with(['article','article.category'])
        //     ->where(function($query) use ($q){
        //         if($q){
        //             $query->OrwhereHas('article', function($query) use($q){
        //                 $query->whereRaw("(name like '%$q%')");
        //             });
        //         }
        //     })
        //     ->select('article_id', 'id', 'price', 'expiration',  DB::raw("SUM(cantRestante) as cantRestante"))
        //     ->where('cantRestante','>', 0)->where('deleted_at', null)->where('expirationStatus', 1)->groupBy('article_id', 'price', 'expiration')->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $sucursal = Sucursal::where('id', $user->sucursal_id)->first();
            if(!$sucursal)
            {
                return redirect()->route('outbox.index')->with(['message' => 'Error.', 'alert-type' => 'error']);
            }
            $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $sucursal->id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
            if(!$gestion)
            {
                return redirect()->route('outbox.index')->with(['message' => 'Error en la gestion.', 'alert-type' => 'error']);
            }
            
            $funcionario = $this->getWorker($user->funcionario_id);

            $unidad = Unit::with(['direction'])->where('id', $user->unidadAdministrativa_id)->first();
            // return $unidad;

            $aux = SolicitudPedido::where('unidad_id',$unidad->id)
                    ->where('deleted_at', null)
                    ->get();
            $ok = SolicitudPedido::where('deleted_at', null)->where('gestion', $gestion->gestion)->where('unidad_id', $unidad->id)->count();
            // return $ok;

            $length = 4;
            $char = 0;
            $type = 'd';
            $format = "%{$char}{$length}{$type}"; // or "$010d";
            $request->merge(['nropedido' => strtoupper($unidad->sigla).'-'.sprintf($format, $ok+1).'/'.$gestion->gestion]);


            if(!$request->article_id)
            {
                return redirect()->route('outbox.index')->with(['message' => 'Ingrese el detalle del pedido para hacer la solicitud.', 'alert-type' => 'error']);
            }


            
            $sol = SolicitudPedido::create([
                'sucursal_id'=>$sucursal->id,
                'fechasolicitud'=> Carbon::now(),
                'gestion' => $gestion->gestion,
                'nropedido' => $request->nropedido,
                'people_id'=> $funcionario->people_id,
                'first_name'=>$funcionario->first_name,
                'last_name'=>$funcionario->last_name,
                'job'=>$funcionario->cargo,
                'direccion_name'=>$unidad->direction->nombre,
                'direccion_id'=>$user->direccionAdministrativa_id,
                'unidad_name'=>$unidad->nombre,
                'unidad_id'=> $user->unidadAdministrativa_id,
                'registerUser_Id'=> $user->id
            ]);            
            $cont = 0;    
            // return $request;    
            while($cont < count($request->article_id))
            {
                SolicitudPedidoDetalle::create([
                        'solicitudPedido_id'            => $sol->id,
                        'sucursal_id'       => $sucursal->id,
                        'gestion' => $gestion->gestion,
                        'article_id'            => $request->article_id[$cont],
                        'cantsolicitada'        => $request->cantidad[$cont],

                        'registerUser_Id'          => Auth::user()->id
                    ]);
                    $cont++;
            }

            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'Solicitud registrada exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('outbox.index')->with(['message' => 'Error...', 'alert-type' => 'error']);
        }
    }


    protected function show($id)
    {
        $sol = SolicitudPedido::with(['solicitudDetalle'])
            ->where('id', $id)
            ->first();
            // return 1;
        
        return view('almacenes.outbox.report', compact('sol'));
    }

    public function deletePedido(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user();
           
            $sol = SolicitudPedido::where('id', $request->id)->first();
            if($sol->status != 'Pendiente' &&  $sol->status != 'Enviado')
            {
                return redirect()->route('outbox.index')->with(['message' => 'El pedido no se encuentra disponible para eliminarlo', 'alert-type' => 'error']);
            }
                        
       
            SolicitudPedidoDetalle::where('solicitudPedido_id', $sol->id)->update(['deleted_at'=> Carbon::now(), 'deletedUser_Id'=> $user->id]);
            $sol->update(['deleted_at'=> Carbon::now(), 'deletedUser_Id'=> $user->id]);
               
            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'Pedido Eliminado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('outbox.index')->with(['message' => 'Error...', 'alert-type' => 'error']);
        }
    }

    public function solicitudEnviada(Request $request)
    {
        SolicitudPedido::where('id', $request->id)->update(['status'=>'Enviado']);
        return redirect()->route('outbox.index')->with(['message' => 'Solicitud enviada exitosamente.', 'alert-type' => 'success']);
    }

    public function confirmarEliminacion(Request $request)
    {
        // return $request;

        $user =Auth::user();
        // return 'Mantenimiento';
        DB::beginTransaction();
        try{
            $pedido = SolicitudPedido::where('id', $request->id)->first();
            // return $pedido;
            $sol = SolicitudEgreso::where('solicitudPedido_id', $request->id)->first();
            // return $sol;
            // $sol = SolicitudEgreso::find($request->id);
    
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


            $sol->update(['deleteuser_id'=>$user->id, 'deleted_at' => Carbon::now(), 'condicion'=>'eliminado']);
            SolicitudPedidoDetalle::where('solicitudPedido_id', $pedido->id)->update(['deleted_at'=>Carbon::now(), 'deletedUser_Id'=>$user->id]);
            $pedido->update(['deletedUser_Id'=>$user->id, 'deleted_at' => Carbon::now(), 'status'=>'eliminado']);



            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('outbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function cancelarEliminacion(Request $request)
    {
        DB::beginTransaction();
        try{
            $pedido = SolicitudPedido::where('id', $request->id)->where('status', 'pendienteeliminacion')->first();
            $sol = SolicitudEgreso::where('solicitudPedido_id', $request->id)->first();

            $sol->update(['condicion'=>'entregado']);
            $pedido->update(['status'=>'Entregado']);

            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'La anulacion de pedido ha sido cancelado exitosamente...', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('outbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
        
    }
}
