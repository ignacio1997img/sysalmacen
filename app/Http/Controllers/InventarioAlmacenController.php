<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioAlmacen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InventarioAlmacenController extends Controller
{
    public function index()
    {
        $data = InventarioAlmacen::where('deleted_at', null)->get();
        return view('almacenes.inventario.browse', compact('data'));
    }

    public function finish(Request $request)
    {
        // return $request;
        $date = date('Y-m-d');
        // return $date;
        DB::beginTransaction();
        try {

            
            $ok = InventarioAlmacen::where('id', $request->id)->first();
            $ok->update(['finish'=>$date, 'status'=>0, 'observation1'=>$request->observation1]);
            DB::commit();
            return redirect()->route('inventory.index')->with(['message' => 'Gestion Cerrada Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('inventory.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }
}
