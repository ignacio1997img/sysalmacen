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
                    [{{$sucursal->nombre}} - Egreso desde {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}}] <br>
                    {{$message}} <br>
                    {{$messagePartida}}
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
                <th>F. EGRESO</th>
                <th>NRO PEDIDO</th>
                <th>OFICINA</th>
                <th>PARTIDA</th>
                <th>ARTICULO</th>
                <th style="width:30px">PRESENTACION</th>
                <th style="width:60px">PRECIO</th>
                <th style="width:60px">CANT.</th>
                <th style="width:60px">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $total = 0;
                $cant = 0;
            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td>{{date('d/m/Y', strtotime($item->fechaegreso))}}</td>
                    <td style="text-align: left">{{$item->nropedido}} </td>
                    <td style="text-align: left">{{ $item->unidad }}</td>
                    <td style="text-align: left">{{ $item->partida}}</td>
                    <td style="text-align: left">{{ $item->articulo }}</td>
                    <td style="text-align: left">{{ $item->presentacion }}</td>
                    <td style="text-align: right">{{ number_format($item->precio,2,',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->cantsolicitada,2,',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->totalbs,2,',', '.') }}</td>         
                </tr>
                @php
                    $count++;
                    $total = $total + ($item->totalbs);
                    $cant = $cant + $item->cantsolicitada;
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="10">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="8" class="text-right"><strong>TOTAL</strong></td>
                <td><strong>{{number_format($cant,2,',', '.')}}</strong></td>
                <td><strong>{{number_format($total,2,',', '.')}}</strong></td>
            </tr>
        </tbody>
    </table>
    <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
    </div>

@endsection