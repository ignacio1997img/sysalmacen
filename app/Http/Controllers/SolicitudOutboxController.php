<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\PeopleExt;
use App\Models\SolicitudCompra;
use App\Models\SolicitudEgreso;
use App\Models\Article;
use App\Models\Partida;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\InventarioAlmacen;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;

class SolicitudOutboxController extends Controller
{
    public function index()
    {
        if(setting('configuracion.maintenance') && !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('almacen_admin'))
        {
            Auth::logout();
            return redirect()->route('maintenance');
        }

        return view('almacenes.outbox.browse');
    }
}
