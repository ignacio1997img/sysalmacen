@extends('voyager::master')

@section('page_title', 'Viendo Detalle Solicitud')
@if (auth()->user()->hasPermission('read_inbox'))
    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 id="subtitle" class="page-title">
                    <i class="voyager-basket"></i> Detalle Solicitud
                </h1>
                <a href="{{ route('inbox.index') }}" class="btn btn-warning btn-add-new">
                    <i class="fa-solid fa-file"></i> <span>Volver</span>
                </a>
                @if ($data->status == 'Enviado')
                    <a data-toggle="modal" data-target="#modal-rechazar" title="Rechazar Solicitud" class="btn btn-sm btn-dark view">
                        <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Rechazar</span>
                    </a> 
                    <a data-toggle="modal" data-target="#myModalAprobar" title="Aprobar Solicitud" class="btn btn-sm btn-success view">
                        <i class="fa-solid fa-thumbs-up"></i> Aprobar
                    </a>  
                @endif
            </div>
        </div>
    @stop

    @section('content')    
        <div id="app">
            <div class="page-content browse container-fluid" >            
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">                                       
                                {{-- <h5 id="subtitle">Solicitud de Compras</h5> --}}
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Almacen</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{strtoupper($data->sucursal->nombre)}}</small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Nro Solicitud</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{strtoupper($data->nropedido)}}</small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Solicitante</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{strtoupper($data->first_name.' '.$data->last_name.' - '.$data->job)}} </small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Fecha de Solicitud</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{date('d/m/Y H:i:s', strtotime($data->fechasolicitud))}}</small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Dirección</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{$data->direccion_name}}</small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <label class="panel-title">Unidad</label>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p><small>{{$data->unidad_name}}</small></p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                                
                                </div>
                                <h5 id="subtitle">Articulo:</h5>
                                <div class="row">
                                            <table id="dataTableStyle" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50px; text-align: center">N°</th>                                                 
                                                        <th style="text-align: center">PARTIDA</th>
                                                        <th style="text-align: center">DETALLE</th>
                                                        <th style="text-align: center">UNIDAD</th>                
                                                        <th width="150px" style="text-align: center">CANTIDAD SOLICITADA</th>
                                                        @if ($data->status == 'Entregado')
                                                            <th width="150px" style="text-align: center">CANTIDAD ENTREGADA</th>  
                                                        @endif               
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    @php
                                                        $numeroitems =1;
                                                    @endphp
                                                    @foreach ($data->solicitudDetalle as $item)
                                                        <tr>
                                                            <td style="text-align: right">{{$numeroitems}}</td>
                                                            <td style="text-align: left">{{$item->article->partida->codigo}} - {{$item->article->partida->nombre}}</td>
                                                            <td style="text-align: left">{{strtoupper($item->article->nombre)}}</td>
                                                            <td style="text-align: center">{{strtoupper($item->article->presentacion)}}</td>
                                                            <td style="text-align: right">{{number_format($item->cantsolicitada, 2, ',', ' ')}}</td>
                                                            @if ($data->status == 'Entregado')
                                                                <td style="text-align: right">{{number_format($item->cantentregada, 2, ',', ' ')}}</td>
                                                            @endif  
                                                        </tr>
                                                        <?php
                                                            $numeroitems++;
                                                        ?>
                                                    @endforeach
                                                </tbody>                                        
                                            </table>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
        
        

            {{-- Modal para rechazar solicitud --}}
            <div class="modal fade" tabindex="-1" id="modal-rechazar" role="dialog">
                <div class="modal-dialog modal-dark">
                    <div class="modal-content">
                        {!! Form::open(['route' => 'inbox.rechazar', 'method' => 'POST']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa-solid fa-thumbs-down"></i> Rechazar Solicitud</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="{{$data->id}}">
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-thumbs-down" style="color: #4d4c4b; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea rechazar la solicitud?</b></p>
                            </div>
                        </div>                
                        <div class="modal-footer">
                            
                                <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, rechazar">
                            
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                        </div>
                        {!! Form::close()!!} 
                    </div>
                </div>
            </div>
        
        
            {{-- Para aprobar las solicitudes de pedidos --}}
            <div class="modal modal-success fade" tabindex="-1" id="myModalAprobar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['route' => 'inbox.aprobar', 'method' => 'post']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa-solid fa-file"></i> Aprobar Solicitud</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="{{$data->id}}">
        
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-file" style="color: rgb(134, 127, 127); font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea aprobar solicitud?</b></p>
                            </div>
                        </div>                
                        <div class="modal-footer">
                            
                                <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, aprobar">
                            
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                        </div>
                        {!! Form::close()!!} 
                    </div>
                </div>
            </div>
    @stop


    @section('css')
        <style>
            input:focus {
                background: rgb(197, 252, 215);
            }

            input:focus{        
                background: rgb(255, 245, 229);
                border-color: rgb(255, 161, 10);
                /* border-radius: 50px; */
            }
            input.text, select.text, textarea.text{ 
                border-radius: 5px 5px 5px 5px;
                color: #000000;
                border-color: rgb(63, 63, 63);
            }

        
            small{font-size: 32px;
                color: rgb(12, 12, 12);
                font-weight: bold;
            }
            #subtitle{
                font-size: 18px;
                color: rgb(12, 12, 12);
                font-weight: bold;
            }


            #detalles {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            #detalles td, #detalles th {
            border: 1px solid #ddd;
            padding: 8px;
            }

            #detalles tr:nth-child(even){background-color: #f2f2f2;}

            #detalles tr:hover {background-color: #ddd;}

            #detalles th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #04AA6D;
                color: white;
            }

            .form-control .select2{
                border-radius: 5px 5px 5px 5px;
                color: #000000;
                border-color: rgb(63, 63, 63);
            }
            

        </style>
    @stop

    @section('javascript')
    <script>
        
    </script>

    @stop
@endif

