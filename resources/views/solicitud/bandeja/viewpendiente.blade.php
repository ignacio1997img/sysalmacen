@extends('voyager::master')

@section('page_title', 'Ver Ingresos')

@if(auth()->user()->hasPermission('read_bandeja'))
    @section('page_header')
    <div class="col-md-6" style="margin: 20px 0px;">
            <a href="{{ route('bandeja.index') }}" class="btn btn-default"><i class="voyager-angle-left"></i> Volver</a>
            <h3 class="text-muted" style="padding-left: 20px"></h3>
        </div>
        @if($derivacion[0]->rechazado == 0 && $derivacion[0]->aprobado == 0)
            <div class="col-md-6 text-right" style="margin-top: 40px;">
                <div class="btn-group" role="group" aria-label="...">
                    @if(auth()->user()->hasPermission('add_bandeja'))
                        <a href="{{route('view_editar_aprobar_solicitud',$solicitud->id)}}" class="btn btn-default"><i class="voyager-edit"></i> Editar y Aprobar Solicitud</a>
                        <button type="button" data-toggle="modal" data-target="#modal-aprobar" title="Derivar" class="btn btn-default"><i class="voyager-forward"></i> Aprobar Solicitud</button>
                        <button type="button" data-toggle="modal" data-target="#modal-rechazar" title="Rechazar" class="btn btn-default"><i class="voyager-warning"></i> Rechazar</button>
                    @endif
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
                    </div>
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
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
                                                <th>Partida</th>
                                                <th>Detalle</th>
                                                <th>Unidad Medida</th>
                                                <th>Cantidad Solicitada</th>
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
                                                </tr>
                                                <?php
                                                    $i++;
                                                ?>
                                             @endforeach                                  
                                        </tbody>
                                        <!-- <tfoot>
                                            <th colspan="4" style="text-align:right"><h5>TOTAL</h5></th>
                                            <th><h4 id="total">Bs.</h4></th>
                                        </tfoot> -->
                                    </table>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {!! Form::open(['route' => 'bandeja_rechazar_solicitud', 'method' => 'POST']) !!}
            <div class="modal modal-danger fade" tabindex="-1" id="modal-rechazar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-warning"></i> Rechazar solicitud</h4>
                        </div>
                        <div class="modal-body">
                        
                            <input type="hidden" name="derivacion_id" value="{{ $derivacion[0]->id }}">
                            <div class="form-group">
                                <label>Motivo del rechazo</label>
                                <textarea name="observacion" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Rechazar</button>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close()!!}


        {!! Form::open(['route' => 'bandeja_aprobar_solicitud', 'method' => 'POST']) !!}
            <div class="modal modal-success fade" tabindex="-1" id="modal-aprobar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-warning"></i> Aprobar Solicitud de Pedido</h4>
                        </div>
                        <div class="modal-body">
                        
                            <input type="hidden" name="derivacion_id" value="{{ $derivacion[0]->id }}">
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="voyager-basket" style="color: green; font-size: 5em;"></i><i class="voyager-check" style="font-size: 5em;"></i>
                                <br>                                
                                <p><b>Aprobar Solicitud...!</b></p>
                                <p><small><b><h4 id="numero"></h4></b></small></p>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Autorizar</button>
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

