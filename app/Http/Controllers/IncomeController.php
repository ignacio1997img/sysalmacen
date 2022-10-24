<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Partida;
use App\Models\Provider;
use App\Models\Article;
use App\Models\Modality;
use App\Models\Sucursal;
use App\Models\SolicitudCompra;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\User;
use DataTables;
use DateTime;
use Doctrine\DBAL\Exception\RetryableException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SucursalUser;



class IncomeController extends Controller
{
    public function index()
    {
        // $datetime1 	= new DateTime('2020-10-13 16:52:52');
        // $datetime2 	= new DateTime('2020-10-11 16:52:52');
        // $interval 	= $datetime1->diff($datetime2);

        // return $interval->format('%a days');




        // $ok = DB::table('donacion_ingresos as di')
        //         ->join('donacion_ingreso_detalles as did', 'did.donacioningreso_id', 'di.id')
        //         ->join('donacion_articulos as da', 'da.id', 'did.donacionarticulo_id')
        //         ->select('di.nrosolicitud', 'da.nombre', 'did.caducidad')
        //         ->where('did.deleted_at', null)
        //         ->where('did.condicion', 1)
        //         ->where('did.cantrestante','>', 0)
        //         ->get();

        


        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->get();
        
        if(count($sucursal) > 1 && count($sucursal) < 1)
        {
            return "Contactese con el administrador";
        }


        $user =Auth::user();
    
        $activo = DB::table('users as u')
                ->join('sucursal_users as su', 'su.user_id', 'u.id')
                ->join('sucursals as s', 's.id', 'su.sucursal_id')
                ->select('u.id as user', 'u.name', 's.id', 's.nombre')
                ->where('u.id',$user->id )
                ->where('su.condicion',1)
                ->first();
  
        if(auth()->user()->isAdmin())
        {
            $income = DB::table('solicitud_compras as sol')
            ->join('facturas as f', 'f.solicitudcompra_id', 'sol.id')
            // ->join('invoice_details as fd', 'fd.invoice_id', 'f.id')
            ->join('modalities as m', 'm.id', 'sol.modality_id')
            ->join('providers as pro', 'pro.id', 'f.provider_id')
            ->join('sucursals as s', 's.id', 'sol.sucursal_id')
            ->select('sol.id', 'sol.stock', 's.nombre as sucursal', 'm.nombre as modalidad', 'sol.nrosolicitud' , 'pro.razonsocial', 'pro.nit', 'f.nrofactura', 'f.fechafactura', 'f.montofactura', 'sol.created_at', 'sol.condicion')
            ->where('sol.deleted_at', null)
            ->orderBy('sol.id', 'DESC')
            ->get();
        }
        else
        {
            $income = DB::table('solicitud_compras as sol')
                ->join('facturas as f', 'f.solicitudcompra_id', 'sol.id')
                // ->join('invoice_details as fd', 'fd.invoice_id', 'f.id')
                ->join('modalities as m', 'm.id', 'sol.modality_id')
                ->join('providers as pro', 'pro.id', 'f.provider_id')
                ->select('sol.id', 'sol.stock', 'm.nombre as modalidad', 'sol.nrosolicitud' , 'pro.razonsocial', 'pro.nit', 'f.nrofactura', 'f.fechafactura', 'f.montofactura', 'sol.created_at', 'sol.condicion')
                ->where('sol.deleted_at', null)
                ->where('sol.sucursal_id', $sucursal->first()->sucursal_id)
                ->orderBy('sol.id', 'DESC')
                ->get();
        }
        
                

        return view('almacenes.income.browse', compact('income'));
    }


    public function create()
    {
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->get();

        if(count($sucursal) > 1 && count($sucursal) < 1)
        {
            return "Contactese con el administrador";
        }

        $da = $this->direccionSucursal($sucursal->first()->sucursal_id);

        $proveedor = Provider::where('condicion',1)->where('sucursal_id',$sucursal->first()->sucursal_id)->get();
        // $partida = Partida::where('codigo','like','3%')->get();
        $partida = Partida::all();
        $modalidad = Modality::all();
        // return $partida;

        return view('almacenes.income.add', compact('sucursal', 'da', 'proveedor', 'partida', 'modalidad'));

    }

    protected function view_ingreso($id)
    {
        $sol = SolicitudCompra::find($id);
        
        $factura = Factura::where('solicitudcompra_id', $sol->id)->where('deleted_at', null)->first();

        $sucursal = Sucursal::find($sol->sucursal_id);

        $modalidad = Modality::find($sol->modality_id);

       
        $unidad = DB::connection('mamore')->table('unidades')
                ->select('nombre')
                ->where('id', $sol->unidadadministrativa)
                ->get();

        $detalle = DB::table('detalle_facturas as df')
                    ->join('articles as a', 'a.id', 'df.article_id')
                    ->join('partidas as p', 'p.id', 'a.partida_id')
                    ->select('p.codigo as partida', 'a.nombre as articulo','a.presentacion','a.id as codigo', 'df.cantsolicitada as cantidad', 'df.precio as precio')
                    ->where('df.hist', 0)
                    ->where('df.deleted_at', null)
                    // ->where('df.condicion', 1)
                    ->where('df.factura_id', $factura->id)
                    ->get();
        
        $proveedor = Provider::find($factura->provider_id);

        
        return view('almacenes.income.report',compact('sol','factura', 'detalle', 'sucursal', 'modalidad', 'unidad', 'proveedor'));
        // return view('income.view', compact('sol','factura', 'detalle', 'sucursal', 'modalidad', 'unidad'));
    }
    protected function view_ingreso_stock($id)
    {
        $sol = SolicitudCompra::find($id);
 
        
        $factura = Factura::where('solicitudcompra_id', $sol->id)->get();

        $sucursal = Sucursal::find($sol->sucursal_id);

        $modalidad = Modality::find($sol->modality_id);

       
        $unidad = DB::connection('mamore')->table('unidades')
                ->select('nombre')
                ->where('id', $sol->unidadadministrativa)
                ->get();

        $detalle = DB::table('detalle_facturas as df')
                    ->join('articles as a', 'a.id', 'df.article_id')
                    ->join('partidas as p', 'p.id', 'a.partida_id')
                    ->select('p.codigo as partida', 'a.nombre as articulo','a.presentacion','a.id as codigo', 'df.cantsolicitada as cantidad', 'df.cantrestante')
                    ->where('df.hist', 0)
                    ->where('df.deleted_at', null)
                    // ->where('df.condicion', 1)
                    ->where('df.factura_id', $factura[0]->id)
                    ->get();
        
        $proveedor = Provider::find($factura[0]->provider_id);

        
        return view('almacenes.income.reportstock',compact('sol','factura', 'detalle', 'sucursal', 'modalidad', 'unidad', 'proveedor'));
        // return view('income.view', compact('sol','factura', 'detalle', 'sucursal', 'modalidad', 'unidad'));
    }


    // funcion para guardar los registro de un ingreso en general
    public function store(Request $request)
    {      
        // return $request;
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $total =0;
            $x = 0;
        
            while($x < count($request->subtotal))
            {
                $total = $total + $request->subtotal[$x];
                $x++;
            }

            if($request->total == $request->montofactura && $request->total == $total && $request->montofactura == $total)
            {
                // return $total;
                $unidad = DB::connection('mamore')->table('unidades')
                        ->select('sigla')
                        ->where('id',$request->unidadadministrativa)
                        ->get();
                // return $unidad;

                $aux = SolicitudCompra::where('unidadadministrativa',$request->unidadadministrativa)
                    ->where('deleted_at', null)
                    ->get();

                    $length = 4;
                    $char = 0;
                    $type = 'd';
                    $format = "%{$char}{$length}{$type}"; // or "$010d";
                    



                $request->merge(['nrosolicitud' => strtoupper($unidad[0]->sigla).'-'.sprintf($format, count($aux)+1)]);
            
                $gestion = Carbon::parse($request->fechaingreso)->format('Y');

                $solicitud = SolicitudCompra::create([
                        'sucursal_id'       => $request->branchoffice_id,
                        'direccionadministrativa' => $request->direccionadministrativa,
                        'unidadadministrativa'     => $request->unidadadministrativa,
                        'modality_id'           => $request->modality_id,
                        'registeruser_id'       => $user->id,
                        'nrosolicitud'          => $request->nrosolicitud,
                        'fechaingreso'          => $request->fechaingreso,
                        'gestion'               => $gestion
                ]);
                
            
                $factura = Factura::create([
                        'solicitudcompra_id'   => $solicitud->id,
                        'provider_id'           => $request->provider_id,
                        'registeruser_id'       => $user->id,
                        'tipofactura'           => $request->tipofactura,
                        'fechafactura'          => $request->fechafactura,
                        'montofactura'          => $total,
                        'nrofactura'            => $request->nrofactura,
                        'nroautorizacion'       => $request->nroautorizacion,
                        'nrocontrol'            => $request->nrocontrol,
                        'fechaingreso'          => $request->fechaingreso, 
                        'gestion'               => $gestion,
                        'sucursal_id'       => $request->branchoffice_id
                ]);
                
                $cont = 0;
        
                while($cont < count($request->article_id))
                {
                    DetalleFactura::create([
                        'factura_id'            => $factura->id,
                        'registeruser_id'       => $user->id,
                        'article_id'            => $request->article_id[$cont],
                        'cantsolicitada'        => $request->cantidad[$cont],
                        'precio'                => $request->precio[$cont],
                        'totalbs'               => $request->subtotal[$cont],
                        'cantrestante'          => $request->cantidad[$cont],
                        'fechaingreso'          => $request->fechaingreso,
                        'gestion'               => $gestion,
                        'sucursal_id'           => $request->branchoffice_id
                    ]);
                    $cont++;
                }
                DB::commit();
                return redirect()->route('income.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
            }
            else
            {
                // return 0;
                return redirect()->route('income.index')->with(['message' => 'El monto de la factura no coincide con el total de la solicitud.', 'alert-type' => 'error']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // return 10;
            return redirect()->route('income.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);            
        }
    }

 
    public function edit($id)
    {

        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->get();

        if(count($sucursal) > 1 && count($sucursal) < 1)
        {
            return "Contactese con el administrador";
        }

        // $da = $this->getDireccion();     
        $da = $this->direccionSucursal($sucursal->first()->sucursal_id);


        $solicitud = SolicitudCompra::find($id);


        // $user = Auth::user();
        // $sucursales = Sucursal::join('sucursal_users as u','u.sucursal_id', 'sucursals.id')
        //             ->select('sucursals.id','sucursals.nombre','u.condicion')
        //             ->where('u.condicion',1)
        //             ->where('u.user_id', $user->id)
        //             ->get();


        $sol = SolicitudCompra::find($id);
        // $facturaux = Factura::where('solicitudcompra_id', $id)->first();

        // $factura = Factura::find($facturaux->id);

        $factura = Factura::where('solicitudcompra_id', $id)->first();
  
        
        $detalle = DB::table('detalle_facturas as df')
                ->join('articles as a', 'a.id', 'df.article_id')
                ->join('partidas as p', 'p.id', 'a.partida_id')
                ->select('df.id as de','a.id as articulo_id', 'a.nombre as articulo', 'a.presentacion', 'p.codigo', 'p.nombre as partida', 'df.cantsolicitada', 'df.precio', 'df.totalbs')
                ->where('df.factura_id', $factura->id)
                ->where('df.deleted_at', null)
                ->get();

        $proveedorselect = Provider::find($factura->provider_id);



        // $proveedor = Provider::all();
        $proveedor = Provider::where('condicion',1)->where('sucursal_id',$sucursal->first()->sucursal_id)->get();
        $partida = Partida::all();

        $modalidad = Modality::all();

        return view('almacenes.income.edit', compact('da', 'solicitud', 'sucursal', 'detalle', 'partida', 'proveedor', 'sol', 'factura', 'proveedorselect','modalidad'));

    }



    public function update(Request $request)
    {  
        $user = Auth::user();
        $gestion = Carbon::parse($request->fechaingreso)->format('Y');
        DB::beginTransaction();
        try {
            if(floatval($request->total) === floatval($request->montofactura))
            {
                //para agregar un numero de solicitud si en caso se cambia la unidad administrativa
                $aux = SolicitudCompra::find($request->id);            
                if($aux->unidadadministrativa != $request->unidadadministrativa)
                {
                    $unidad = DB::connection('mamore')->table('unidades')
                        ->select('sigla')
                        ->where('id',$request->unidadadministrativa)
                        ->first();

                    $aux = SolicitudCompra::where('unidadadministrativa',$request->unidadadministrativa)
                        ->where('deleted_at', null)
                        ->get();

                    $length = 4;
                    $char = 0;
                    $type = 'd';
                    $format = "%{$char}{$length}{$type}"; // or "$010d";

                    $request->merge(['nrosolicitud' => strtoupper($unidad->sigla).'-'.sprintf($format, count($aux)+1)]);
                }
                else
                {
                    $request->merge(['nrosolicitud' => $aux->nrosolicitud]);
                }

                SolicitudCompra::where('id',$request->id)->update([
                        'sucursal_id'       => $request->branchoffice_id,
                        'direccionadministrativa' => $request->direccionadministrativa,
                        'unidadadministrativa'     => $request->unidadadministrativa,
                        'modality_id'           => $request->modality_id,
                        'registeruser_id'       => $user->id,
                        'nrosolicitud'          => $request->nrosolicitud,
                        'fechaingreso'          => $request->fechaingreso,
                        'gestion'               => $gestion
                ]);
    
            
                Factura::where('solicitudcompra_id',$request->id)->update([
                        'provider_id'           => $request->provider_id,
                        'registeruser_id'       => $user->id,
                        'tipofactura'           => $request->tipofactura,
                        'fechafactura'          => $request->fechafactura,
                        'montofactura'          => $request->montofactura,
                        'nrofactura'            => $request->nrofactura,
                        'nroautorizacion'       => $request->nroautorizacion,
                        'nrocontrol'            => $request->nrocontrol,
                        'fechaingreso'          => $request->fechaingreso, 
                        'gestion'               => $gestion
                ]);

                $factura = Factura::where('solicitudcompra_id',$request->id)->first();
                // return $factura;
                
                DetalleFactura::where('factura_id', $factura->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);
                // DetalleFactura::where('factura_id', $factura->id)->delete();
                // return 1;
                $cont = 0;

                // return 1;
        
                while($cont < count($request->article_id))
                {
                    DetalleFactura::create([
                        'factura_id'            => $factura->id,
                        'registeruser_id'       => $user->id,
                        'article_id'            => $request->article_id[$cont],
                        'cantsolicitada'        => $request->cantidad[$cont],
                        'precio'                => $request->precio[$cont],
                        'totalbs'               => $request->totalbs[$cont],
                        'cantrestante'          => $request->cantidad[$cont],
                        'fechaingreso'          => $request->fechaingreso,
                        'gestion'               => $gestion
                    ]);
                    $cont++;
                }

                DB::commit();
                // return 1;
                return redirect()->route('income.index')->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
            }
            else
            {
                return redirect()->route('income.edit', $request->id)->with(['message' => 'El monto de la factura no coincide con el total de la solicitud.', 'alert-type' => 'error']);
            }
        } catch (\Throwable $th) {
            
            DB::rollback();
            // return 0;
            return redirect()->route('income.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
            
        }
        // return redirect()->route('income.index');
        // return redirect('admin/income');
    }

    public function salida($id)
    {
        // return $id;
        // $factura = Factura::where('solicitudcompra_id', $id)->first();
        // $detalle = DetalleFactura::where('factura_id', $factura->id)->where('deleted_at', null)->get();

        $detalle = DB::table('solicitud_egresos as se')
                    ->join('detalle_egresos as de', 'de.solicitudegreso_id', 'se.id')
                    ->join('detalle_facturas as df', 'df.id', 'de.detallefactura_id')
                    ->join('facturas as f', 'f.id', 'df.factura_id')

                    ->where('se.deleted_at', null)
                    ->where('de.deleted_at', null)
                    ->where('df.deleted_at', null)//opcional
                    ->where('f.deleted_at', null)
                    ->where('f.solicitudcompra_id', $id)
                    // ->select('se.id', 'se.nropedido', 'se.fechasolicitud', 'se.fechaegreso')
                    // ->orderBy('se.nropedido')->get();

                    ->select('se.id', 'se.nropedido', 'se.fechasolicitud', 'se.fechaegreso')
                    ->groupBy('se.id', 'se.nropedido', 'se.fechasolicitud', 'se.fechaegreso')
                    ->orderBy('se.nropedido')->get();


                    // return $detalle;

        return view('almacenes.income.salida', compact('detalle'));
    }














    public function destroy(Request $request)
    {       
        
        DB::beginTransaction();
        try{

            $sol = SolicitudCompra::find($request->id);
            SolicitudCompra::where('id', $sol->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);
    
            $fac = Factura::where('solicitudcompra_id',$sol->id)->get();
            Factura::where('solicitudcompra_id', $sol->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);

            DetalleFactura::where('factura_id', $fac[0]->id)->update(['deleted_at' => Carbon::now(), 'condicion' => 0]);

            DB::commit();
            return redirect()->route('income.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('income.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
            

    }





    protected function ajax_unidad_administrativa($id)//para la view de ingreso y egreso
    {
        return DB::connection('mamore')->table('unidades')
                        ->where('deleted_at', null)
                        ->where('direccion_id',$id)
                        ->select('*')
                        ->orderBy('nombre', 'asc')
                        ->get();
    }







    // protected function ajax_unidad_solicitante($id)//para la view de ingreso y egreso
    // {
    //     return RequestingUnit::where('executingunit_id', $id)->get();
    // }
    protected function ajax_article($id)
    {
        // $sucursal = SucursalUser::where('user_id', Auth::user()->id)->where('condicion', 1)->where('deleted_at', null)->first();
        return Article::where('partida_id', $id)->where('condicion', 1)->get();
    }
    protected function ajax_presentacion($id)
    {
        return Article::find($id);
    }
}
