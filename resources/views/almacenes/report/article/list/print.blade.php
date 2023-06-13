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
                    UNIDAD DE ALMACENES MATERIALES Y SUMINISTROS<br>
                    
                    [Lista de Artículos - {{$sucursal->nombre}} {{date('d/m/Y')}}]
                </h3>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 12px" border="1" class="print-friendly" cellspacing="0" cellpadding="5">

        <thead>
            <tr>
                <th>N&deg;</th>
                <th  style="text-align: center; width:50%">PARTIDA</th>
                <th style="text-align: center">ARTICULO</th>
                <th>PRESENTACION</th>
            </tr>
        </thead>
        <tbody>
            @php
                            $count = 1;
                        @endphp
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->codigo }} - {{ $item->partida }}</td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->presentacion }}</td>
                                                                                          
                            </tr>
                            @php
                                $count++;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="4">No se encontraron registros.</td>
                            </tr>
            @endforelse
          
        </tbody>
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