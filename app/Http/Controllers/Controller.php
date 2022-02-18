<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getIdDireccionInfo() {
        try {
            return DB::connection('mysqlgobe')->table('direccionadministrativa as d')
                        ->select('*')
                        ->get();
        } catch (\Throwable $th) {
            return 111;
        }
    }
}
