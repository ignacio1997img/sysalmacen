@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    @if ($sol->status == 'eliminado')
        <div id="watermark">
            <img src="{{ asset('images/anulado.png') }}" /> 
        </div>
    @endif

    @if ($sol->status == 'Rechazado')
        <div id="watermark">
            <img src="{{ asset('images/rechazado.png') }}" /> 
        </div>
    @endif
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table  cellspacing="0" width="100%">
        <tr>
            <td style="width: 15%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 15px">
                    SOLICITUD DE CONTRATACIONES DE OBRAS, BIENES, SERVICIOS GENERALES Y CONSULTARIA/FORMULARIO PEDIDO DE MATERIALES Y/O SERVICIOS<br>
                </h3>     
            </td>
            <td style="text-align: right; width:15%">
                <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 15px">
                    N° {{$sol->nropedido}}
                    <br>
                    {{date('d/m/Y', strtotime($sol->fechasolicitud))}}
                    {{-- <br> --}}
                   
                    {{-- <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small> --}}
                </h3>
            </td>
        </tr>
    </table>
    <br>
    <table border="1" cellspacing="0" cellpadding="5" class="text-center" width="100%" style="font-size: 8pt">
        <tr>
            <th style="text-align: left; width:120px">SOLICITANTE</th>
            <td style="text-align: left">{{strtoupper($sol->first_name.' '.$sol->last_name.' '.$sol->job)}}</td>                        
        </tr>
        <tr>
            <th style="text-align: left">UNIDAD SOLICITANTE</th>
            <td style="text-align: left">
                @if ($sol->unidad_name)
                    {{strtoupper($sol->unidad_name.' - '.$sol->direccion_name)}}
                @else
                    {{strtoupper($sol->direccion_name)}}
                @endif
            </td>                        
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th style="width:5px">N&deg;</th>
                <th style="text-align: center; width:70px">PARTIDA</th>
                <th style="text-align: center">DESCRIPCION</th>
                <th style="text-align: center; width:100px">UNIDAD</th>
                <th style="text-align: right; width:5px">CANTIDAD</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $numeroitems = 1;
            ?>
            @forelse ($sol->solicitudDetalle as $data)
                <tr>
                    <td style="text-align: right">{{$numeroitems}}</td>
                    <td style="text-align: center">{{$data->article->partida->codigo}}</td>
                    <td style="text-align: left">{{strtoupper($data->article->nombre)}}</td>
                    <td style="text-align: center">{{strtoupper($data->article->presentacion)}}</td>
                    <td style="text-align: right">{{number_format($data->cantsolicitada, 2, ',', ' ')}}</td>
                </tr>
                <?php
                    $numeroitems++;
                ?>
            @empty
                <tr style="text-align: center">
                    <td colspan="5">No se encontraron registros.</td>
                </tr>
            @endforelse
         
        </tbody>
       
    </table>

    {{-- <div class="text">
        <p style="font-size: 13px;"><b>NOTA:</b> La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.</p>
    </div> --}}
    <br>
    <br><br>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Entrege Conforme</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Responsable</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Recibí Conforme</b>
            </td>
        </tr>
    </table>

@endsection

@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
          
    </style>
@stop