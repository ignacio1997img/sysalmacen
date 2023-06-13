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
                        <br>
                        {{$sucursal->nombre}}
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
                <th style="width:5px">N&deg;</th>
                <th style="text-align: center">DESCRIPCION</th>
                <th style="text-align: center">CANTIDAD INICIAL <br>01/01/{{$gestion}}</th>
                <th style="text-align: center">SALDO INICIAL<br>01/01/{{$gestion}}<br>(Bs)</th>
                <th style="text-align: center">CANTIDAD FINAL <br>31/12/{{$gestion}}</th>
                <th style="text-align: center">SALDO FINAL <br>31/12/{{$gestion}}<br>(Bs)</th>
            </tr>
        </thead>
        <tbody>
                    @php
                        $count = 1;
                        $cant1 =0;
                        $cant2 =0;
                        $total1 =0;
                        $total2 =0;
                    @endphp
                    @forelse ($partida as $item)
                        <tr>
                            <td>{{ $count }}</td>
                            <td style="text-align: left">{{ $item->codigo }} - {{ $item->nombre }}</td>
                            <td style="text-align: right">{{ number_format($item->cantidadinicial,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->totalinicial,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->cantfinal,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->totalfinal, 2, ',', '.')}}</td>


                                                                                    
                        </tr>
                        @php
                            $count++;
                            $cant1 = $cant1 + $item->cantidadinicial;
                            $total1 = $total1 + $item->totalinicial;

                            $cant2 = $cant2 + $item->cantfinal;
                            $total2 = $total2 + $item->totalfinal;
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
            <tr>
                <th colspan="2" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($cant1,2, ',', '.')}}</th>
                <th style="text-align: right">{{number_format($total1,2, ',', '.')}}</th>
                <th style="text-align: right">{{number_format($cant2,2, ',', '.')}}</th>
                <th style="text-align: right">{{number_format($total2,2, ',', '.')}}</th>
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