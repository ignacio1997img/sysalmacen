<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromCollection;

class AnualPartidaExport implements FromView
{
    function __construct($partida, $gestion) {
		// function __construct($partida, $afp, $cc, $centralize, $program, $group, $type_generate, $type_render) {
        $this->partida = $partida;
        $this->gestion = $gestion;

    }

    public function view(): View
    {
        return view('almacenes.report.inventarioAnual.partidaGeneral.excel',
		[
			'partida' => $this->partida,
            'gestion' => $this->gestion
		]);
    }
}
