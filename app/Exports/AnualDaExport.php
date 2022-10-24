<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AnualDaExport implements FromView
{
    function __construct($data) {
		$this->datas = $data;
    }

    public function view(): View
    {
        return view('almacenes.report.inventarioAnual.direccionAdministrativa.excel',
        [
            'data'=>$this->datas,
        ]);
    }
}
