<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromCollection;

class AnualPartidaExport implements FromView
{
    function __construct($data, $gestion) {
		// function __construct($data, $afp, $cc, $centralize, $program, $group, $type_generate, $type_render) {
        $this->datas = $data;
        $this->gestion = $gestion;

    }

    public function view(): View
    {
        return view('almacenes.report.inventarioAnual.partidaGeneral.excel',
		[
			'data' => $this->datas,
            'gestion' => $this->gestion
		]);
    }
}
