<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;
use App\Models\SucursalDireccion;
use App\Models\InventarioAlmacen;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use App\Models\SucursalUnidadPrincipal;
use App\Models\SucursalUser;
use App\Models\Unit;
use Carbon\Carbon;

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
    public function list($type, $search = null){
        $user = Auth::user();
        // dump($user);

        // $sucursal = SucursalUser::where('user_id', $user->id)->where('condicion', 1)->where('deleted_at', null)->first();
        
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $user->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada
        
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
            ->whereRaw($query_filter)
            ->orderBy('id', 'DESC')->paginate($paginate);

        return view('almacenes.outbox.list', compact('data', 'gestion'));
    }

    public function create()
    {
        // return 1;
        $user = Auth::user();


        // $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        $sucursal = Sucursal::where('id', $user->sucursal_id)->first();
        $gestion = InventarioAlmacen::where('status', 1)->where('sucursal_id', $user->sucursal_id)->where('deleted_at', null)->first();//para ver si hay gestion activa o cerrada

        // return 1;
        $funcionario = $this->getWorker($user->funcionario_id);

        $mainUnit = SucursalUnidadPrincipal::where('sucursal_id', $user->sucursal_id)->where('status', 1)->where('deleted_at', null)->first();
        // return $mainUnit;
        $query = '';
        if($mainUnit)
        {
            $query = ' or s.unidadadministrativa = '.$mainUnit->unidadAdministrativa_id;
        }
        $unidad = 'null';
        if($funcionario->id_unidad)
        {
            $unidad = $funcionario->id_unidad;
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
        // }
        // else
        // {
        //     $data = null;
        // }
        // return $data;

        // return count($data);




        return view('almacenes.outbox.edit-add', compact('gestion', 'sucursal', 'funcionario', 'data'));
    }



    //Funcion ajax para obtener los articulos disponible en el almacen
    public function ajaxProductExists()
    {
        $q = request('q');
        $user = Auth::user();


        $funcionario = $this->getWorker($user->funcionario_id);

        $mainUnit = SucursalUnidadPrincipal::where('sucursal_id', $user->sucursal_id)->where('status', 1)->where('deleted_at', null)->first();
        // return $mainUnit;
        $query = '';
        if($mainUnit)
        {
            $query = ' or s.unidadadministrativa = '.$mainUnit->unidadAdministrativa_id;
        }
        $unidad = 'null';
        if($funcionario->id_unidad)
        {
            $unidad = $funcionario->id_unidad;
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
                ->select('s.id', 'f.id as factura_id', 'a.id as article_id', 'a.nombre as nombre', 'a.image', 'a.presentacion')
                ->whereRaw("(nombre like '%$q%')")
                ->groupBy('article_id')
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
            
            $funcionario = $this->getWorker($user->funcionario_id);
            // dd($funcionario->id_unidad);

            $unidad = Unit::where('id', $funcionario->id_unidad)->first();

            $aux = SolicitudPedido::where('unidad_id',$unidad->id)
                    ->where('deleted_at', null)
                    ->get();

            $length = 4;
            $char = 0;
            $type = 'd';
            $format = "%{$char}{$length}{$type}"; // or "$010d";
            $request->merge(['nropedido' => strtoupper($unidad->sigla).'-'.sprintf($format, count($aux)+1)]);
            // return $unidad;




            if(!$request->article_id)
            {
                return redirect()->route('outbox.index')->with(['message' => 'Ingrese el detalle del pedido para hacer la solicitud.', 'alert-type' => 'error']);
            }

            // $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
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
            
            $sol = SolicitudPedido::create([
                'sucursal_id'=>$sucursal->id,
                'fechasolicitud'=> Carbon::now(),
                'gestion' => $gestion->gestion,
                'nropedido' => $request->nropedido,
                'people_id'=> $funcionario->people_id,
                'first_name'=>$funcionario->first_name,
                'last_name'=>$funcionario->last_name,
                'job'=>$funcionario->cargo,
                'direccion_name'=>$funcionario->direccion,
                'direccion_id'=>$funcionario->id_direccion,
                'unidad_name'=>$funcionario->unidad,
                'unidad_id'=> $funcionario->id_unidad,
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
        
        return view('almacenes.outbox.report', compact('sol'));
    }

    public function deletePedido(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user();
           
            $sol = SolicitudPedido::where('id', $request->id)->first();
                        
       
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
}
