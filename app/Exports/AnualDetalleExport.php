<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AnualDetalleExport implements FromView
{
    function __construct($data, $gestion) {
		$this->datas = $data;
		$this->gestion = $gestion;

    }

    public function view(): View
    {
        return view('almacenes.report.inventarioAnual.detalleGeneral.excel',
        [
            'data'=>$this->datas,
            'gestion'=>$this->gestion
        ]);
    }
}
