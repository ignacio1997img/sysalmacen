<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\SolicitudCompra;
use App\Models\SolicitudEgreso;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\SucursalDireccion;
use App\Models\SucursalSubAlmacen;
use App\Models\SucursalUnidadPrincipal;
use App\Models\SucursalUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Prophecy\Promise\ReturnPromise;

class SucursalController extends Controller
{
    //
    public function index()
    {

        // $data = SolicitudCompra::all();
        // foreach($data as $item)
        // {
        //     $aux = SucursalSubAlmacen::where('sucursal_id', $item->sucursal_id)->first();
        //     SolicitudCompra::where('sucursal_id', $item->sucursal_id)->update(['subSucursal_id'=>$aux->id]);
        // }
        $data = SolicitudEgreso::all();
        foreach($data as $item)
        {
            $aux = SucursalSubAlmacen::where('sucursal_id', $item->sucursal_id)->first();
            SolicitudEgreso::where('sucursal_id', $item->sucursal_id)->update(['subSucursal_id'=>$aux->id]);
        }

        // return 1;
        $sucursal = Sucursal::where('deleted_at', null)->where('condicion', 1)->get();
        // return $sucursal;
        return view('almacenes.sucursal.browse', compact('sucursal'));
    }

    public function indexDireccion($sucursal)
    {
        $sucursal = Sucursal::find($sucursal);
        $da = Direction::where('deleted_at', null)->get();
        $data = DB::connection('mamore')->table('direcciones as d')
            ->join('sysalmacen.sucursal_direccions as s', 's.direccionAdministrativa_id', 'd.id')
            ->where('s.deleted_at', null)
            // ->where('s.status',1)
            ->where('s.sucursal_id', $sucursal->id)
            ->select('d.id as direccion_id', 'd.nombre', 's.id', 's.status')
            ->get();
        // return $sucursal;
        $principal = SucursalUnidadPrincipal::with(['unidad'])->where('sucursal_id', $sucursal->id)->where('deleted_at', null)->get();
        $sub = SucursalSubAlmacen::where('sucursal_id', $sucursal->id)->where('deleted_at', null)->get();
        // return $principal;

            // return $data;
        return view('almacenes.sucursal.da.browse', compact('sucursal', 'data', 'da', 'principal', 'sub'));
    }

    public function storeDireccion(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $ok = SucursalDireccion::where('sucursal_id', $request->sucursal_id)
                ->where('direccionAdministrativa_id', $request->direccion_id)
                ->where('deleted_at',null)->first();

            if($ok)
            {
                if($ok->status == 1)
                {
                    return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'La Direccion Administrativa ya se encuentra registrada.', 'alert-type' => 'error']);
                }
                else
                {
                    return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'La Direccion Administrativa se encuentra inactivo.', 'alert-type' => 'error']);
                }
            }
            
            SucursalDireccion::create(['sucursal_id'=>$request->sucursal_id, 'direccionAdministrativa_id'=>$request->direccion_id]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function destroyDireccion(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalDireccion::where('id',$request->id)->update(['deleted_at'=>Carbon::now()]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }
    public function habilitarDireccion(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalDireccion::where('id',$request->id)->update(['status'=>1]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registro Habilitado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function inhabilitarDireccion(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalDireccion::where('id',$request->id)->update(['status'=> 0]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registro Inhabilitado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }





    public function storeUnidad(Request $request) 
    {
        // return $request;
        DB::beginTransaction();

        $ok = SucursalUnidadPrincipal::where('deleted_at', null)->where('sucursal_id', $request->sucursal_id)->first();
        if($ok)
        {
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ya cuenta con un almacen principal.', 'alert-type' => 'error']);
        }
        try {
            SucursalUnidadPrincipal::create([
                'sucursal_id' => $request->sucursal_id,
                'direccionAdministrativa_id' => $request->direccion_id,
                'unidadAdministrativa_id' => $request->unidad_id,
                'registerUser_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Unidad principal registrada.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function destroyUnidad(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalUnidadPrincipal::where('id',$request->id)->update(['deleted_at'=>Carbon::now(), 'deleteUser_id' => Auth::user()->id]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function storeSubAlmacen(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalSubAlmacen::create([
                'sucursal_id' => $request->sucursal_id,
                'name' => $request->name,
                // 'registerUser_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Sub Almacen Registrado Exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function destroySubAlmacen(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            SucursalSubAlmacen::where('id',$request->id)
                ->update(['deleted_at'=>Carbon::now(), 
                        // 'deleteUser_id' => Auth::user()->id
                ]);
            DB::commit();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Sub Almacen Eliminado Exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


}
