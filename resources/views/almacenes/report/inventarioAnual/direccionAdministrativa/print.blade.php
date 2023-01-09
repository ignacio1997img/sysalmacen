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
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center">DIRECCIONES ADMINISTRATIVA</th>
                <th colspan="3" style="text-align: center">BS.</th>
            </tr>
            <tr>
                {{-- <th style="width:5px">NRO&deg;</th> --}}
                <th style="text-align: center">SALDO INICIAL</th>
                <th style="text-align: center">INGRESO</th>
                <th style="text-align: center">SALIDAS</th>
                {{-- <th style="text-align: center">SALDO FINAL</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                        $count = 1;
                        $si = 0;
                        $i = 0;
                        $s = 0;
                        $sf = 0;
                    @endphp
                    @forelse ($direction as $item)
                        <tr>
                            <td>{{ $count }}</td>
                            <td style="text-align: left">{{ $item->nombre }}</td>
                            <td style="text-align: right">{{ number_format($item->inicio,2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->ingreso,2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->salida,2,',', '.')}}</td>
                            @php
                                $aux =0;
                                if (($item->inicio + $item->ingreso) >= $item->salida)
                                {
                                    $aux = ($item->inicio + $item->ingreso) - $item->salida;
                                }
                                else
                                {
                                    $aux = $item->salida - ($item->inicio + $item->ingreso);
                                }   
                                $sf = $sf + $aux;                                                    
                            @endphp
                            {{-- <td style="text-align: right">{{ number_format($aux,2)}}</td>                             --}}
                                                                                    
                        </tr>
                        @php
                            $count++;
                            $si = $si + $item->inicio;
                            $i = $i + $item->ingreso;
                            $s = $s + $item->salida;
                            // $sf = 0;
                            
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="5">No se encontraron registros.</td>
                        </tr>
                    @endforelse
            <tr>
                <th colspan="2" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($si,2,',', '.')}}</th>
                <th style="text-align: right">{{number_format($i,2,',', '.')}}</th>
                <th style="text-align: right">{{number_format($s,2,',', '.')}}</th>
                {{-- <th style="text-align: right">{{number_format($sf,2)}}</th> --}}
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