<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class DireccionAdministrativaFormField extends AbstractHandler
{
    protected $codename = 'direccionadministrativa';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('vendor.voyager.formfields.direccionadministrativa', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}