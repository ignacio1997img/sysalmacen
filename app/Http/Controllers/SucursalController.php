<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\SucursalDireccion;
use App\Models\SucursalUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SucursalController extends Controller
{
    //
    public function index()
    {
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
            ->select('d.nombre', 's.id', 's.status')
            ->get();

            // return $data;
        return view('almacenes.sucursal.da.browse', compact('sucursal', 'data', 'da'));
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
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registrado Habilitado.', 'alert-type' => 'success']);
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
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Registrado Inhabilitado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sucursal-da.index',['sucursal'=>$request->sucursal_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }
}
