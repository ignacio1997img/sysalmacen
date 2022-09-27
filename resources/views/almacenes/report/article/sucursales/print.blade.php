@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:70%">
                <h2 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h2>
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    DETALLE DE ALMACENES (BIENES DE CONSUMO)<br>
                    Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}}
                </h3>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th style="width:5%" rowspan="2">Nro&deg;</th>
                <th rowspan="2">Descripción (Item)</th>
                <th rowspan="2">Unidad de Medida</th>
                <th rowspan="2">Precio Unitario</th>
                <th style="text-align: center" colspan="4">Cantidad</th>
                <th style="text-align: center" colspan="4">Valores</th>
            </tr>
            <tr>
                <th>Saldo Inicial</th>
                <th >Entradas</th>
                <th >Salidas</th>
                <th >Saldo Final</th>
                <th>Saldo Inicial</th>
                <th >Entradas</th>
                <th >Salidas</th>
                <th >Saldo Final</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="4" style="text-align: right">Total</th>
                <th>Saldo Inicial</th>
                <th >Entradas</th>
                <th >Salidas</th>
                <th >Saldo Final</th>
                <th>Saldo Inicial</th>
                <th >Entradas</th>
                <th >Salidas</th>
                <th >Saldo Final</th>
            </tr>
        </tbody>
       
    </table>
    <table style="width: 100%; font-size: 12px">
        <thead>
            
        </thead>
    </table>
    <div class="row" style="font-size: 9pt">
        {{-- <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p> --}}
        <p style="text-align: right">Total - Artículo Disponible: BS. </p>
    </div>

    <div class="text">
        <p style="font-size: 13px;"><b>NOTA:</b> La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.</p>
    </div>

@endsection