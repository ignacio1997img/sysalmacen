<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\SucursalUser;

class ProviderController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->first();
      
        $query_filter = 'sucursal_id = '.$sucursal->sucursal_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filter = 1;
        }
        // $category = Category::where('deleted_at', null)->where('status', 1)->whereRaw($query_filtro)->get();
        $provider = Provider::whereRaw($query_filter)->get();
        return view('almacenes.provider.browse', compact('provider'));
    }


    public function list(){
        $q = request('q');
        $data = Provider::whereRaw($q ? '(nit like "%'.$q.'%" or razonsocial like "%'.$q.'%" or responsable like "%'.$q.'%")' : 1)
                    ->where('deleted_at', NULL)->where('condicion',1)->get();
        return response()->json($data);
    }
}
