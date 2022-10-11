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
                    Stock Disponible 
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
                <th style="width:5%">NRO&deg;</th>
                <th style="width:15%">F. INGRESO</th>
                <th style="width:25%">ENTIDAD + NRO COMPRA</th>
                <th style="width:25%">PROVEEDOR</th>
                <th style="width:25%">NRO</th>
                <th style="width:25%">ARTICULO</th>
                <th style="width:25%">PRESENTACION</th>
                <th style="width:25%">PRECIO</th>
                <th style="width:25%">CANT.</th>
                <th style="width:15%">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $total = 0;
                $cant = 0;
            @endphp
            @forelse ($data as $item)
                <tr style="text-align: center">
                    <td>{{ $count }}</td>
                    <td>{{date('d/m/Y', strtotime($item->fechaingreso))}}</td>
                    <td>{{ $item->modalidad }} <br>{{$item->nrosolicitud}} </td>
                    <td>{{ $item->proveedor }}</td>
                    <td>{{ $item->tipofactura=='Orden_Compra'? 'Orden de Compra':'Nro Factura'}}<br>{{$item->nrofactura}}</td>
                    <td>{{ $item->articulo }}</td>
                    <td>{{ $item->presentacion }}</td>
                    <td style="text-align: right">{{ number_format($item->precio,2) }}</td>
                    <td style="text-align: right">{{ number_format($item->cantrestante,2) }}</td>
                    <td style="text-align: right">{{ number_format($item->precio * $item->cantrestante,2) }}</td>                                                                                          
                </tr>
                @php
                    $count++;
                    $total = $total + ($item->cantrestante * $item->precio);
                    $cant = $cant + $item->cantrestante;
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="6">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="8" class="text-right"><strong>TOTAL</strong></td>
                <td><strong>{{number_format($cant,2)}}</strong></td>
                <td><strong>{{number_format($total,2)}}</strong></td>
            </tr>
        </tbody>
    </table>
    <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
    </div>

@endsection