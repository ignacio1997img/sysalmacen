@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                        RESUMEN DE VALORES FISCALES
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                    01 de enero {{$gestion}} Al 31 diciembre {{$gestion}}
                    <br>
                    (Expresado en Bolivianos)
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
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center">DESCRIPCION (ITEM)</th>
                <th rowspan="2" style="text-align: center">U. DE MEDIDA</th>
                <th rowspan="2" style="text-align: center">PRECIO UNITARIO</th>
                <th colspan="4" style="text-align: center">CANTIDAD</th>
                <th colspan="4" style="text-align: center">VALORES</th>
                {{-- <th style="text-align: center">SALDO FINAL</th> --}}
            </tr>
            <tr>
                {{-- <th style="width:5px">NRO&deg;</th> --}}
                <th style="text-align: center">SALDO INICIAL</th>
                <th style="text-align: center">ENTRADAS</th>
                <th style="text-align: center">SALIDAS</th>
                <th style="text-align: center">SALDO FINAL</th>
                <th style="text-align: center">SALDO INICIAL</th>
                <th style="text-align: center">ENTRADAS</th>
                <th style="text-align: center">SALIDAS</th>
                <th style="text-align: center">SALDO FINAL</th>
                {{-- <th style="text-align: center">SALDO FINAL</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $cIni = 0;
                $vIni = 0;
                $cEnt = 0;
                $vEnt = 0;
                $cSal = 0;
                $vSal = 0;
                $cFin = 0;
                $vFin = 0;

            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td style="text-align: left">{{ $item->id }} - {{ $item->nombre }}</td>
                    <td style="text-align: right">{{ $item->presentacion}}</td>
                    <td style="text-align: right">{{ $item->precio}}</td>
                    <td style="text-align: right">{{ number_format($item->cInicial,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->cEntrada,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->cSalida,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->cFinal,2)}}</td>

                    <td style="text-align: right">{{ number_format($item->vInicial,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->vEntrada,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->vSalida,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->vFinal,2)}}</td>
                                                                            
                </tr>
                @php
                    $count++;
                    $cIni = $cIni + $item->cInicial;
                    $vIni = $cIni + $item->vInicial;

                    $cEnt = $cEnt + $item->cEntrada;
                    $vEnt = $vEnt + $item->vEntrada;

                    $cSal = $cSal + $item->cSalida;
                    $vSal = $vSal + $item->vSalida;

                    $cFin = $cFin + $item->cFinal;
                    $vFin = $vFin + $item->vFinal;
                    
                    
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="6">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="4" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($cIni,2)}}</th>
                <th style="text-align: right">{{number_format($cEnt,2)}}</th>
                <th style="text-align: right">{{number_format($cSal,2)}}</th>
                <th style="text-align: right">{{number_format($cFin,2)}}</th>

                <th style="text-align: right">{{number_format($vIni,2)}}</th>
                <th style="text-align: right">{{number_format($vEnt,2)}}</th>
                <th style="text-align: right">{{number_format($vSal,2)}}</th>
                <th style="text-align: right">{{number_format($vFin,2)}}</th>
            </tr>
        </tbody>
       
    </table>
    {{-- <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
    </div> --}}

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
                <b>Firma Contabilidad</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Responsable</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Firma Responsable</b>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Contabilidad</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Firma DGAA - DAF</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma DGAA - DAF</b> --}}
            </td>
        </tr>
    </table>

@endsection