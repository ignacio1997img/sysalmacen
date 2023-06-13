@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:60%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                        RESUMEN DE VALORES FISCALES <br>
                        {{$sucursal->nombre}}
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                    01 de enero {{$gestion}} Al 31 diciembre {{$gestion}}
                    <br>
                    (Expresado en Bolivianos)
                </small>
            </td>
            <td style="text-align: right; width:20%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 12px" border="1" class="print-friendly" cellspacing="0" cellpadding="5">

        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center">DESCRIPCION (ITEM)</th>
                <th rowspan="2" style="text-align: center">U. DE MEDIDA</th>
                <th rowspan="2" style="text-align: center">PRECIO UNITARIO</th>
                <th colspan="4" style="text-align: center">CANTIDAD</th>
                <th colspan="4" style="text-align: center">VALORES</th>
            </tr>
            <tr>
                <th style="text-align: center">SALDO INICIAL</th>
                <th style="text-align: center">ENTRADAS</th>
                <th style="text-align: center">SALIDAS</th>
                <th style="text-align: center">SALDO FINAL</th>
                <th style="text-align: center">SALDO INICIAL</th>
                <th style="text-align: center">ENTRADAS</th>
                <th style="text-align: center">SALIDAS</th>
                <th style="text-align: center">SALDO FINAL</th>
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
            @forelse ($collection as $item)
                <tr>
                            <td>{{ $count }}</td>
                            <td>{{ $item['id'] }} - {{ $item['nombre'] }}</td>
                            <td style="text-align: right">{{ $item['presentacion']}}</td>
                            <td style="text-align: right">{{ $item['precio']}}</td>
                            <td style="text-align: right">{{ number_format($item['saldo'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['entrada'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['salida'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['final'],2,',', '.')}}</td>

                            <td style="text-align: right">{{ number_format($item['bssaldo'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['bsentrada'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['bssalida'],2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item['bsfinal'],2,',', '.')}}</td>
                                                                            
                </tr>
                @php
                    $count++;
                    $cIni = $cIni + $item['saldo'];
                    $vIni = $vIni + $item['bssaldo'];

                    $cEnt = $cEnt + $item['entrada'];
                    $vEnt = $vEnt + $item['bsentrada'];

                    $cSal = $cSal + $item['salida'];
                    $vSal = $vSal + $item['bssalida'];

                    $cFin = $cFin + $item['final'];
                    $vFin = $vFin + $item['bsfinal'];                            
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="12">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                        <th colspan="4" style="text-align: left">Total</th>
                        <th style="text-align: right">{{number_format($cIni,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($cEnt,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($cSal,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($cFin,2,',', '.')}}</th>
        
                        <th style="text-align: right">{{number_format($vIni,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($vEnt,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($vSal,2,',', '.')}}</th>
                        <th style="text-align: right">{{number_format($vFin,2,',', '.')}}</th>
            </tr>
        </tbody>
       
    </table>

    <div class="text">
        <p style="font-size: 12px;"><b>NOTA:</b> La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.</p>
    </div>
    <br>
    <br><br>
    <br>
    <table style="width: 100%; font-size: 12px">
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
    <table style="width: 100%; font-size: 12px">
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

@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
        /* @media print { div{ page-break-inside: avoid; } }  */
          
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }
          
    </style>
@stop