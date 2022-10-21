<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ArticleEgressOfficeExport implements FromView
{
    function __construct($data) 
    {
        $this->datas = $data;
    }

    public function view(): View
    {
        return view('almacenes.report.article.egressOffice.excel',
		[
			'data' => $this->datas
		]);
    }
}
