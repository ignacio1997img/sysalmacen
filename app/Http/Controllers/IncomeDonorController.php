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
use App\Models\DonacionArchivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Illuminate\Support\Str;
use file;
use PhpParser\Node\Stmt\Return_;

class IncomeDonorController extends Controller
{
    
    public function index()
    {

        $income = DB::table('donacion_ingresos as di')
            ->join('centros as c', 'c.id', 'di.centro_id')
            ->select('di.id', 'di.nrosolicitud', 'c.nombre','di.observacion', 'di.fechadonacion', 'di.fechaingreso', 'di.condicion', 'di.stock')
            ->where('di.deleted_at', null)
            // ->where('di.condicion','!=',0)
            ->get();



        return view('donacion-sedeges.incomedonor.browse', compact('income'));
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

        return view('donacion-sedeges.incomedonor.add', compact('categoria', 'centrotipo'));        
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


            $onuempresa_id =null;
            $persona_id = null;
            
            if($request->tipodonante == 0 || $request->tipodonante == 1)
            {
                $onuempresa_id = $request->donante_id;
            }
            if($request->tipodonante == 2)
            {
                $persona_id = $request->donante_id;
            }
            // dd($onuempresa_id);


            $income = DonacionIngreso::create(
                    [
                        'centro_id'         => $request->centro_id,
                        'fechadonacion'     => $request->fechadonacion,
                        'fechaingreso'      => $request->fechaingreso,
                        'tipodonante'       => $request->tipodonante,
                        'nrosolicitud'      => $request->nrosolicitud,
                        'registeruser_id'   => $user->id,
                        'observacion'       => $request->observacion,
                        'gestion'           => $gestion,
                        'onuempresa_id'     => $onuempresa_id,                    
                        'persona_id'        => $persona_id                    
                    ]
                );
            
          

            $cont = 0;
            while($cont < count($request->articulo_id))
            {
                DonacionIngresoDetalle::create([
                    'donacioningreso_id'    => $income->id,
                    'donacionarticulo_id'   => $request->articulo_id[$cont],
                    'registeruser_id'       => $user->id,
                    'estado'                => $request->estado[$cont],
                    'caracteristica'        => $request->caracteristica[$cont],
                    'cantidad'              => $request->cantidad[$cont],
                    'precio'                => $request->precio[$cont],
                    'cantrestante'          => $request->cantidad[$cont],
                    'caducidad'             => $request->caducidad[$cont]
                ]);
                $cont++;
            }
            // return $request;
            
            $file = $request->file('archivos');

            $i=0;
            if ($file) {
                // dd($file);
                for ($i=0; $i < count($file); $i++) { 
                    
                    $nombre_origen = $file[$i]->getClientOriginalName();
                    
                    $newFileName = Str::random(20).time().'.'.$file[$i]->getClientOriginalExtension();
                    
                    $dir = "DonacionSedeges/income/".date('F').date('Y');
                    
                    Storage::makeDirectory($dir);
                    Storage::disk('public')->put($dir.'/'.$newFileName, file_get_contents($file[$i]));
                    
                    DonacionArchivo::create([
                        'entrada'               => 1,
                        'nombre_origen'         => $nombre_origen,
                        'donacioningreso_id'    => $income->id,
                        'ruta'                  => $dir.'/'.$newFileName,
                        'user_id'               => $user->id
                    ]);
                }
            }


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
                ->select('dc.nombre as categoria', 'da.nombre', 'da.presentacion', 'd.estado', 'd.caracteristica', 'd.cantidad', 'd.precio', 'd.caducidad')
                ->where('d.donacioningreso_id', $do->id)
                ->where('d.deleted_at', null)
                ->get();

        $centro = Centro::find($do->centro_id);

        if($do->tipodonante == 0 || $do->tipodonante == 1)
        {
            $donante = DonadorEmpresa::find($do->onuempresa_id);
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
        // return $do;

        if($do->tipodonante == 2)
        {
            $donante = DonadorPersona::find($do->persona_id);
            $donante->tipo = "PERSONA INDIVIDUAL";
            $donante->name = $donante->nombre;
            $donante->nit = $donante->ci;


        }
        // $donante = DonadorPersona::find(1);
        // return $donante;
        // return 3;
        if($do->tipodonante == 3)
        {
            $donante = new IncomeController();
            $donante->tipo="Anónimo";
            $donante->name="Anónimo";
            $donante->nit="XXXXXXXX";
            $donante->tel="XXXXXXXX";
        }
        // return $donante->tipo;
        
        return view('donacion-sedeges.incomedonor.report',compact('detalle', 'do', 'centro', 'donante'));
    }

    public function show_stock($id)
    {
        $ingreso = DonacionIngreso::find($id);


        $detalle = DB::table('donacion_ingreso_detalles as did')
                ->join('donacion_articulos as da', 'da.id', 'did.donacionarticulo_id')
                ->join('donacion_categorias as dc', 'dc.id', 'da.categoria_id')
                ->select('dc.nombre as categoria', 'da.nombre as articulo', 'da.presentacion','did.estado', 'did.caracteristica', 'did.cantrestante', 'did.caducidad')
                ->where('did.deleted_at', null)
                ->where('donacioningreso_id', $id)
                ->get();

        $archivos = DonacionArchivo::where('deleted_at', null)
                    ->where('donacioningreso_id', $ingreso->id)
                    ->get();
        // return $detalle;

        // return $archivos;
        return view('donacion-sedeges.incomedonor.reportstock', compact('ingreso', 'detalle', 'archivos'));


    }
    
    public function show_stock_disponible()
    {
        return 2;
        // return view('incomedonor.stock');
    }


    public function edit($id)
    {
        // return $id;
        $categoria = DonacionCategoria::where('deleted_at', null)
                ->where('condicion', 1)->get();
        $centrotipo = CentroCategoria::where('deleted_at', null)
        ->where('condicion', 1)->get();

        $ingreso = DonacionIngreso::find($id);
        // return $ingreso;
        if($ingreso->tipodonante == 2)
        {    
            // $donante = DonadorPersona::find($ingreso->persona_id);
            // $donante->nombre= $donante->nombre;
            // $donante->ci= $donante->ci;
            
            $donante = DB::table('donador_personas')
                ->select('id', 'nombre', 'ci')
                ->where('condicion',1)
                ->where('deleted_at', null)
                ->get();
            
            // return $donante;
   
        }

        if($ingreso->tipodonante == 0 || $ingreso->tipodonante == 1)
        {
            // $donante = DonadorEmpresa::find($ingreso->onuempresa_id);
            // $donante->nombre= $donante->razon;
            // $donante->ci= $donante->nit;

            $donante = DB::table('donador_empresas')
                    ->select('id', 'razon as nombre', 'nit as ci')
                    ->where('tipo',$ingreso->tipodonante)
                    ->where('condicion',1)
                    ->where('deleted_at', null)
                    ->get();
            // return $donante;

        }
        $anonimo =0;
        if($ingreso->tipodonante == 3)
        {
            $anonimo = 1;
            $donante=0;
        }
        // return $donante;
        $detalle = DB::table('donacion_ingreso_detalles as did')
                ->join('donacion_articulos as da', 'da.id', 'did.donacionarticulo_id')
                ->join('donacion_categorias as dc', 'dc.id', 'da.categoria_id')
                ->select('dc.nombre as categoria', 'da.nombre', 'da.id as articulo_id','da.presentacion', 'did.cantidad','did.estado','did.caracteristica', 'did.precio', 'did.caducidad')
                ->where('did.donacioningreso_id', $id)
                ->where('did.deleted_at', null)
                ->get();

        $archivos = DonacionArchivo::where('deleted_at', null)
                ->where('donacioningreso_id', $id)
                ->get();

        return view('donacion-sedeges.incomedonor.edit', compact('categoria', 'centrotipo', 'ingreso', 'donante', 'detalle', 'anonimo','archivos'));
    }
    


    public function update(Request $request)
    {
        // return $request;
        $user = Auth::user();
        $gestion = Carbon::parse($request->fechaingreso)->format('Y');
        DB::beginTransaction();
        try {
            //para agregar un numero de solicitud si en caso se cambia la unidad administrativa
            $aux = DonacionIngreso::find($request->id);            
            if($aux->centro_id != $request->centro_id)
            {
                // $unidad = DB::connection('mysqlgobe')->table('unidadadminstrativa')
                //     ->select('sigla')
                //     ->where('ID',$request->unidadadministrativa)
                //     ->get();

                $aux = DonacionIngreso::where('centro_id',$request->centro_id)
                    ->where('deleted_at', null)
                    ->get();

                $centro = Centro::find($request->centro_id);


                $length = 4;
                $char = 0;
                $type = 'd';
                $format = "%{$char}{$length}{$type}"; // or "$010d";

                $request->merge(['nrosolicitud' => strtoupper($centro->sigla).'-'.sprintf($format, count($aux)+1)]);
            }
            else
            {
                $request->merge(['nrosolicitud' => $aux->nrosolicitud]);
            }

         
            $onuempresa_id =null;
            $persona_id = null;
            
            if($request->tipodonante == 0 || $request->tipodonante == 1)
            {
                $onuempresa_id = $request->donante_id;
            }
            if($request->tipodonante == 2)
            {
                $persona_id = $request->donante_id;
            }

            
            DonacionIngreso::where('id', $request->id)->update(
                [
                    'centro_id'         => $request->centro_id,
                    'fechadonacion'     => $request->fechadonacion,
                    'fechaingreso'      => $request->fechaingreso,
                    'tipodonante'       => $request->tipodonante,
                    'nrosolicitud'      => $request->nrosolicitud,
                    'registeruser_id'   => $user->id,
                    'observacion'       => $request->observacion,
                    'gestion'           => $gestion,
                    'onuempresa_id'     => $onuempresa_id,                    
                    'persona_id'        => $persona_id                     
                ]
            );
           
            DonacionIngresoDetalle::where('donacioningreso_id', $request->id)->delete();
            $cont = 0;
            
            while($cont < count($request->articulo_id))
            {
                DonacionIngresoDetalle::create([
                    'donacioningreso_id'    => $request->id,
                    'donacionarticulo_id'   => $request->articulo_id[$cont],
                    'registeruser_id'       => $user->id,
                    'estado'                => $request->estado[$cont],
                    'caracteristica'        => $request->caracteristica[$cont],
                    'cantidad'              => $request->cantidad[$cont],
                    'precio'                => $request->precio[$cont],
                    'cantrestante'          => $request->cantidad[$cont],
                    'caducidad'             => $request->caducidad[$cont]
                ]);
                $cont++;
            }

            $file = $request->file('archivos');

        
            $i=0;
            if ($file) {
                // dd($file);
                for ($i=0; $i < count($file); $i++) { 
                    
                    $nombre_origen = $file[$i]->getClientOriginalName();
                    
                    $newFileName = Str::random(20).time().'.'.$file[$i]->getClientOriginalExtension();
                    
                    $dir = "DonacionSedeges/income/".date('F').date('Y');
                    
                    Storage::makeDirectory($dir);
                    Storage::disk('public')->put($dir.'/'.$newFileName, file_get_contents($file[$i]));
                    
                    $ok =DonacionArchivo::create([
                        'entrada'               => 1,
                        'nombre_origen'         => $nombre_origen,
                        'donacioningreso_id'    => $request->id,
                        'ruta'                  => $dir.'/'.$newFileName,
                        'user_id'               => $user->id
                    ]);
                    
                }
               
            }

            DB::commit();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ingreso Editado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

  
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();

            $sol = DonacionIngreso::find($request->id);

            DonacionIngreso::where('id', $request->id)->update(['deleted_at' => Carbon::now(), 'deleteuser_id' => $user->id, 'condicion' => 0]);
            DonacionIngresoDetalle::where('donacioningreso_id', $sol->id)->update(['deleted_at' => Carbon::now(),'deleteuser_id' => $user->id, 'condicion' => 0]);
            DonacionArchivo::where('donacioningreso_id', $sol->id)
                ->update(['deleted_at' => Carbon::now(),'deleteuser_id' => $user->id]);

            DB::commit();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ingreso Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('incomedonor.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy_file(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try
        {
            $user = Auth::user();

            DonacionArchivo::where('id', $request->id)
                ->update(['deleted_at' => Carbon::now(),'deleteuser_id' => $user->id]);

            DB::commit();
            return redirect()->route('donacion-sedeges.incomedonor.edit', $request->ingreso_id)->with(['message' => 'Archivo Eliminado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('donacion-sedeges.incomedonor.edit', $request->ingreso_id)->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


    protected function ajax_article($id)
    {
        return DonacionArticulo::where('categoria_id', $id)->where('condicion', 1)->get();
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


    public function ajax_search_donante(Request $request)
    {
        $donante = DonadorEmpresa::where('deleted_at', null);
        if($request->tipo == "0")
        {
            return $donante->where('tipo', 0)->get();
            if($request->tipo == "1")
            {
                return $donante->where('tipo', 1)->get();
            }
            while($request->tipo == "2")
            {
                return $donante->where('tipo', 2)->get();
            }

            if($request->tipo == null)
            {
                return $donante->get();
            }

        }
    }
}
