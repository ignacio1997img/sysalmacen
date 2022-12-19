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
                    REPORTE DE USUARIOS ACTIVOS<br>
                    
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
                <th>ALMACEN</th>
                <th>USUARIOS</th>
                <th style="width:60px">INGRESO <br>
                    CANT.</th>
                <th style="width:60px">INGRESO <br>
                    BS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $bs = 0;
                $cant = 0;
            @endphp
            @forelse ($data as $item)
                <tr style="text-align: center">
                    <td>{{ $count }}</td>
                    <td style="text-align: left">{{ $item->almacen }}</td>

                    @php
                        $funcionarios = Illuminate\Support\Facades\DB::connection('mamore')->table('people as p')
                                ->join('sysalmacen.users as u', 'u.funcionario_id', 'p.id')
                                ->join('sysalmacen.sucursal_users as su', 'su.user_id', 'u.id')

                                ->where('su.condicion', 1)
                                ->where('u.role_id', '!=', 1)
                                ->where('su.sucursal_id', $item->sucursal_id)

                                ->select('p.id', 'p.ci', 'p.first_name', 'p.last_name', 'su.sucursal_id')
                                ->orderBy('p.last_name', 'asc')
                                ->get();
                    @endphp


                    <td style="text-align: left">
                        @foreach ($funcionarios as $func)
                            {{$func->ci}} - {{$func->first_name}} {{$func->last_name}} <br>
                        @endforeach
                    </td>
                    {{-- <td style="text-align: right">{{ $item->almacen}}</td> --}}
                    <td style="text-align: right">{{ number_format($item->cEntrada,2)}}</td>
                    <td style="text-align: right">{{ number_format($item->vEntrada,2)}}</td>                                                                                    
                </tr>
                @php
                    $count++;
                    $bs = $bs + ($item->cEntrada);
                    $cant = $cant + $item->vEntrada;
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="5">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                <td><strong>{{number_format($bs,2)}}</strong></td>
                <td><strong>{{number_format($cant,2)}}</strong></td>
            </tr>
        </tbody>
    </table>
    <div class="row" style="font-size: 9pt">
        {{-- <p style="text-align: right">Total - Artículo Disponible: BS. {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p> --}}
    </div>

@endsection