<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class SucursalFormField extends AbstractHandler
{
    protected $codename = 'sucursal';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('vendor.voyager.formfields.sucursal', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}