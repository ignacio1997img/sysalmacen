@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp
    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    UNIDAD DE ALMACENES MATERIALES Y SUMINISTROS
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px; text-transform: uppercase;">
                    Acta de Recepción de Materiales y Suministros <br>
                    {{$sol->sucursal->nombre}}<br>                    
                    {{$modalidad->nombre}} - {{$sol->nrosolicitud}}
                    <br>
                    {{ date('d', strtotime($sol->fechaingreso)) }} de {{ $months[intval(date('m', strtotime($sol->fechaingreso)))] }} de {{ date('Y', strtotime($sol->fechaingreso)) }}
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table class="text-center" width="100%" style="font-size: 8pt">
        <tr>
            <th style="text-align: center">PROVEEDOR</th>
            <th style="text-align: center">NIT</th>
            <th style="text-align: center">
                @if ($factura->tipofactura != 'Orden_Compra')
                    NRO FACTURA
                @else
                    NRO ORDEN DE COMPRA
                @endif
            </th>
            <th style="text-align: center">FECHA INGRESO</th>
        </tr>
        <tr>
            <td style="text-align: center">{{$proveedor->razonsocial}}</td>
            <td style="text-align: center">{{$proveedor->nit}}</td>
            <td style="text-align: center">{{$factura->nrofactura}}</td>
            <td style="text-align: center">{{\Carbon\Carbon::parse($sol->fechaingreso)->format('d/m/Y')}}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>

            <tr>
                <th style="width:5px">N&deg;</th>
                <th style="text-align: center">Artículo</th>
                <th style="text-align: center">Codigo Articulo</th>
                <th style="text-align: center">Presentacion</th>
                <th style="text-align: center">Cantidad</th>
                <th style="text-align: center">Precio Unit.</th>
                <th style="text-align: center">Total Parcial</th>
            </tr>
        </thead>
        <tbody>
            @php
                $numeroitems = 1; $suma_Total = 0; $total =0;
            @endphp
            @forelse ($detalle as $data)
                <tr>
                    <td>{{$numeroitems}}</td>
                    <td style="text-align: left">{{$data->articulo}}</td>
                    <td style="text-align: right">{{$data->codigo}}</td>
                    <td style="text-align: center">{{$data->presentacion}}</td>                                                    
                    <td style="text-align: right">{{number_format($data->cantidad,2)}}</td>
                    <td style="text-align: right">{{number_format($data->precio,2)}}</td>
                    <td style="text-align: right">{{number_format($data->cantidad * $data->precio,2)}}</td>
                </tr>
                <?php
                    $total+= $data->cantidad * $data->precio;
                    $numeroitems++;
                ?>
            @empty
                <tr style="text-align: center">
                    <td colspan="7">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6"style="text-align: letf"><strong>TOTAL</strong></td>
                <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
            </tr>
        </tbody>
       
    </table>
    <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
    </div>

    <div class="text">
        <p style="font-size: 13px;"><b>NOTA:</b> La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.</p>
    </div>
    <br>
    <br><br>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Responsable Almacen</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Responsable</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Jefe de Contrataciones</b>
            </td>
        </tr>
    </table>

@endsection