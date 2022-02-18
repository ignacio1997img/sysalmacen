<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Models\DonacionArticulo;
use App\Models\DonacionCategoria;
use App\Models\CentroCategoria;
use App\Models\DonadorEmpresa;
use App\Models\DonadorPersona;
use App\Models\DonacionIngreso;
use App\Models\DonacionIngresoDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class IncomeDonorController extends Controller
{
    
    public function index()
    {
        $income = DB::table('donacion_ingresos as di')
            ->join('centros as c', 'c.id', 'di.centro_id')
            ->select('di.id', 'di.nrosolicitud', 'c.nombre', 'di.fechadonacion', 'di.fechaingreso', 'di.condicion')
            ->where('di.deleted_at', null)
            ->where('di.condicion','!=',0)
            ->get();

            // return $income;
        return view('incomedonor.browse', compact('income'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoria = DonacionCategoria::where('deleted_at', null)
                ->where('condicion', 1)->get();
        $centrotipo = CentroCategoria::where('deleted_at', null)
        ->where('condicion', 1)->get();

        return view('incomedonor.add', compact('categoria', 'centrotipo'));        
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $gestion = Carbon::parse($request->fechaingreso)->format('Y');




            $aux = DonacionIngreso::where('centro_id', $request->centro_id)
                ->where('deleted_at', null)
                ->get();
                
            $centro = Centro::find($request->centro_id);

                $length = 4;
                $char = 0;
                $type = 'd';
                $format = "%{$char}{$length}{$type}"; // or "$010d";
        



            $request->merge(['nrosolicitud' => strtoupper($centro->sigla).'-'.sprintf($format, count($aux)+1)]);                






            $income = DonacionIngreso::create(
                    [
                        'centro_id'         => $request->centro_id,
                        'fechadonacion'     => $request->fechadonacion,
                        'fechaingreso'      => $request->fechaingreso,
                        'tipodonante'       => $request->tipodonante,
                        'nrosolicitud'      => $request->nrosolicitud,
                        'registeruser_id'   => $user->id,
                        'gestion'           => $gestion,
                        'donante_id'        => $request->donante_id                    
                    ]
                );
            
            // return $request;

            $cont = 0;
            while($cont < count($request->articulo_id))
            {
                DonacionIngresoDetalle::create([
                    'donacioningreso_id'    => $income->id,
                    'donacionarticulo_id'   => $request->articulo_id[$cont],
                    'registeruser_id'       => $user->id,
                    'cantidad'              => $request->cantidad[$cont],
                    'precio'                => $request->precio[$cont],
                    'cantrestante'          => $request->cantidad[$cont],
                    'caducidad'             => $request->caducidad[$cont]
                ]);
                $cont++;
            }

            // return $request;





            DB::commit();
            return redirect()->route('incomedonor.index')->with(['message' => 'Donacion Registrada Exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);

        }
    }

    public function show($id)
    {
        $do = DonacionIngreso::find($id);
        // $detalle = DonacionIngresoDetalle::where('donacioningreso_id', $do->id)->get();
 
 
        $detalle = DB::table('donacion_ingreso_detalles as d')
                ->join('donacion_articulos as da', 'da.id', 'd.donacionarticulo_id')
                ->join('donacion_categorias as dc', 'dc.id', 'da.categoria_id')
                ->select('dc.nombre as categoria', 'da.nombre', 'da.presentacion', 'd.cantidad', 'd.precio', 'd.caducidad')
                ->where('d.donacioningreso_id', $do->id)
                ->where('d.deleted_at', null)
                ->get();
        $centro = Centro::find($do->centro_id);

        if($do->tipodonante == 0 || $do->tipodonante == 1)
        {
            $donante = DonadorEmpresa::find($do->donante_id);
            $donante->name = $donante->razon;

            
            if($do->tipodonante == 0)
            {
                $donante->tipo = "EMPRESA";
            }
            else
            {
                $donante->tipo = "ONG";
            }
   
        }
        else
        {
            $donante = DonadorPersona::find($do->donante_id);
            $donante->tipo = "PERSONA INDIVIDUAL";
            $donante->name = $donante->nombre;
            $donante->nit = $donante->ci;


        }
        
        return view('incomedonor.report',compact('detalle', 'do', 'centro', 'donante'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

  
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();

            $sol = DonacionIngreso::find($request->id);
            DonacionIngreso::where('id', $sol->id)->update(['deleted_at' => Carbon::now(), 'deleteuser_id ' => $user->id, 'condicion' => 0]);

            DonacionIngresoDetalle::where('donacioningreso_id', $sol->id)->update(['deleted_at' => Carbon::now(),'deleteuser_id ' => $user->id, 'condicion' => 0]);

            DB::commit();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


    protected function ajax_article($id)
    {
        return DonacionArticulo::where('categoria_id', $id)->get();
    }

    protected function ajax_presentacion($id)
    {
        return DonacionArticulo::find($id);
    }

    protected function ajax_centro_acogida($id)
    {
        return Centro::where('deleted_at', null)
                ->where('condicion', 1)->where('centrocategoria_id', $id)->get();
    }

    protected function ajax_income_donante($id)
    {
        if($id == "0")
        {
            $donacion = DonadorEmpresa::where('deleted_at', null)
                        ->where('condicion', 1)
                        ->where('tipo', 0)
                        ->select('razon as nombre','nit as ci', 'id')
                        ->get();
        }
        else
        {
            if($id == "1")
            {
                $donacion = DonadorEmpresa::where('deleted_at', null)
                        ->where('condicion', 1)
                        ->where('tipo', 1)
                        ->select('razon as nombre', 'nit as ci', 'id')
                        ->get();
            }
            else
            {
                $donacion = DonadorPersona::where('deleted_at', null)->where('condicion', 1)->get();
            }
        }
        return $donacion;
    }
}
