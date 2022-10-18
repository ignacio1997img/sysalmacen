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
                    [Lista de Proveedores - {{$sucursal->nombre}}]
                    {{-- [{{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}}] --}}
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
                <th style="width:5%">N&deg;</th>
                <th style="text-align: center">NIT</th>
                <th style="text-align: center">RAZON SOCIAL</th>
                <th style="text-align: center">RESPONSABLE</th>
                <th style="text-align: center" >DIRECCIÓN</th>
                <th style="text-align: center">TELÉFONO</th>
                <th style="text-align: center">FAX</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td style="text-align: right" >{{ $item->nit }}</td>
                    <td>{{ $item->razonsocial }}</td>
                    <td>{{ $item->responsable }}</td>
                    <td>{{ $item->direccion }}</td>
                    <td style="text-align: right" >{{ $item->telefono }}</td>
                    <td>{{ $item->fax }}</td>
                </tr>
                @php
                    $count++;
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="7">No se encontraron registros.</td>
                </tr>
            @endforelse
          
        </tbody>
    </table>

@endsection