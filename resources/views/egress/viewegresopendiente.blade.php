@extends('voyager::master')

@section('page_title', 'Viendo Egreso Pendiente')

@if(auth()->user()->hasPermission('read_egres'))
    @section('page_header')
    <div class="col-md-6" style="margin: 20px 0px;">
            <a href="{{ route('bandeja.index') }}" class="btn btn-default"><i class="voyager-angle-left"></i> Volver</a>
            <h3 class="text-muted" style="padding-left: 20px"></h3>
        </div>
        @if(auth()->user()->hasPermission('add_egres'))
            <div class="col-md-6 text-right" style="margin-top: 40px;">
                <div class="btn-group" role="group" aria-label="...">
                        <button type="button" data-toggle="modal" data-target="#modal-aprobar" title="Derivar" class="btn btn-primary"><i class="voyager-check"></i> Entregar Articulos</button>
                </div>
            </div>   
        @endif
    @stop

    @section('content')
        <div class="page-content read container-fluid div-phone">
            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Funcionario</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$derivacion[0]->de_nombre}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Unidad Administrativa</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$unidad[0]->Nombre}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-3">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Fecha Solicitud</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$solicitud->fechasolicitud}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-3">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Nro Proceso</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$solicitud->nroproceso}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="panel-title">Detalles de la Solicitud</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <table class="table table-bordered table-hover">
                                        <?php
                                            $total = 0;
                                        ?>
                                        <thead>
                                            <tr>
                                                <th>Nro&deg;</th>
                                                <th>Partidass</th>
                                                <th>Detalle</th>
                                                <th>Unidad Medida</th>
                                                <th>Cantidad Solicitada</th>
                                                <th>Cantidad A Entregar</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $i =1;
                                        ?>
                                        <tbody>
                                            @foreach($detalle as $data)     
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$data->codigo}}</td>
                                                    <td>{{$data->nombre}}</td>
                                                    <td>{{$data->presentacion}}</td>
                                                    <td>{{$data->cantidad}}</td>
                                                    <td>{{$data->cantidadentregar}}</td>
                                                </tr>
                                                <?php
                                                    $i++;
                                                ?>
                                             @endforeach                                  
                                        </tbody>
                                    </table>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


        {!! Form::open(['route' => 'egreso_store_egreso_pendiente', 'method' => 'POST']) !!}
            <div class="modal modal-primary fade" tabindex="-1" id="modal-aprobar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header btn-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-basket"></i> Entregar Articulo</h4>
                        </div>
                        <div class="modal-body">
                        
                            <input type="hidden" name="solicitud_id" value="{{ $solicitud->id}}">
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="voyager-basket" style="color: green; font-size: 5em;"></i><i class="voyager-check" style="font-size: 5em;"></i>
                                <br>                                
                                <p><b>Entregar Articulo....!</b></p>
                                <p><small><b><h4 id="numero"></h4></b></small></p>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close()!!}  
       

        

        
    @stop

    @section('css')
 
    @endsection

    @section('javascript')
 
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif