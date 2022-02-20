<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Centro;

class NotificationController extends Controller
{

    //para los productos de donaciones que estan en vigencia................
    protected function ajax_notificacion_donacion()
    {
        $datelle = DB::table('donacion_ingresos as di')
                ->join('donacion_ingreso_detalles as did', 'did.donacioningreso_id', 'di.id')
                ->join('donacion_articulos as da', 'da.id', 'did.donacionarticulo_id')
                ->select('di.id','di.nrosolicitud', 'da.nombre', 'did.caducidad')
                ->where('did.deleted_at', null)
                ->where('did.caducidad','!=', null)
                ->where('did.condicion', 1)
                ->where('did.cantrestante','>', 0)
                ->get();


            $retrasados = [];
            foreach ($datelle as $mov)
            {
                $fecha = Carbon::parse($mov->caducidad);

                $dias = $fecha->diffInDays();

                if($dias <= 5) {
                    // if ($mov->fecharestraso != null)
                    // {
                    //     MovimientoDocumentacion::where('id', $mov->id)
                    //         ->update(['fecharetraso' => Carbon::now()]);
                    // }
                    $retrasados[] = $mov;
                }
            }
            return $retrasados;


    }
}
