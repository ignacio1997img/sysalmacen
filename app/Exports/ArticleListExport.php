<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ArticleListExport implements FromView
{
    function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('almacenes.report.article.list.excel',
		[
			'data' => $this->data
		]);
    }
}
