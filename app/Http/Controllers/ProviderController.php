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
        $user = Auth::user();
        // $sucursal = SucursalUser::
        // $busine_id = $user->busine_id;
        // return $user;
        $query_filtro = 'busine_id = '.$user->busine_id;
        $query_article = 'id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filtro = 1;
            $query_article = 1;
        }
        $provider = Provider::where();
        return view('almacenes.provider.browse', compact('provider'));
    }
}
