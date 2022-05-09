<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use App\Models\DonacionArchivo;
use App\Models\DonacionCategoria;
use App\Models\DonacionEgreso;
use App\Models\DonacionIngreso;
use App\Models\DonacionEgresoDetalle;
use App\Models\DonacionIngresoDetalle;

class DonationStockController extends Controller
{
   public function stock_view()
   {
      $income = DB::table('donacion_ingresos as di')
            ->join('centros as c', 'c.id', 'di.centro_id')
            ->select('di.id', 'di.nrosolicitud', 'c.nombre','di.observacion', 'di.fechadonacion', 'di.fechaingreso', 'di.condicion', 'di.stock')
            ->where('di.deleted_at', null)
            ->where('di.stock', 1)
            // ->where('di.condicion','!=',0)
            ->get();  
      return view('donacion-sedeges.stock.stock', compact('income'));
   }
}
