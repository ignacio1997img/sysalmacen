@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:60%">
                <h2 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h2>
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    UNIDAD DE ALMACENES MATERIALES Y SUMINISTROS<br>
                    [Ingreso desde {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}}]
                </h3>
            </td>
            <td style="text-align: right; width:20%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" class="print-friendly" cellspacing="0" cellpadding="3">

        <thead>
            <tr>
                <th colspan="9">PARTIDA: {{$partida->codigo}} {{$partida->nombre}}</th>
            </tr>
            <tr>
                <th>N&deg;</th>
                <th>N&deg; SOLICITUD</th>
                <th>FECHA INGRESO </th>
                <th>N&deg; FACTURA</th>
                <th>ARTICULO</th>
                <th>PRESENTACION</th>
                <th>CANTIDAD</th>
                <th>PRECIO U.</th>
                <th>SUB TOTAL</th>
            </tr>
        </thead>
        <tbody>
                        @php
                            $count = 1;
                            $total =0;
                        @endphp
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->nrosolicitud}} </td>
                                <td>{{date('d/m/Y', strtotime($item->fechaingreso))}}</td>
                                <td>{{ $item->nrofactura }}</td>
                                <td>{{ $item->articulo }}</td>
                                <td>{{ $item->presentacion }}</td>
                                <td style="text-align: right">{{ number_format($item->cantsolicitada,2,',', '.')  }}</td>
                                <td style="text-align: right">{{ number_format($item->precio,2,',', '.')  }}</td>
                                <td style="text-align: right"><small>{{ number_format($item->totalbs,2,',', '.') }}</small></td>                            
                            </tr>
                            @php
                                $count++;
                                $total = $total + $item->totalbs;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="9">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <th colspan="8" style="text-align: right">Total</th>
                            <th style="text-align: right"><small>Bs. {{ number_format($total,2,',', '.')}}</small></th>
                        </tr>
        </tbody>
    </table>
    <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total - BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
    </div>

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