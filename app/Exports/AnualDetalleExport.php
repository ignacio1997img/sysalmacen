<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AnualDetalleExport implements FromView
{
    function __construct($collection, $gestion) {
		$this->collection = $collection;
		$this->gestion = $gestion;

    }

    public function view(): View
    {
        return view('almacenes.report.inventarioAnual.detalleGeneral.excel',
        [
            'collection'=>$this->collection,
            'gestion'=>$this->gestion
        ]);
    }
}
