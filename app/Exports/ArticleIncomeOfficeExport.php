<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ArticleIncomeOfficeExport implements FromView
{
    function __construct($data) 
    {
        $this->datas = $data;
    }

    public function view(): View
    {
        return view('almacenes.report.article.incomeOffice.excel',
		[
			'data' => $this->datas
		]);
    }
}
