@extends('voyager::master')

@section('page_title', 'Ver Ingresos')

@if(auth()->user()->hasPermission('add_bandeja'))
    @section('page_header')
    <div class="col-md-6" style="margin: 20px 0px;">
            <a href="{{ route('bandeja.index') }}" class="btn btn-default"><i class="voyager-angle-left"></i> Volver</a>
            <h3 class="text-muted" style="padding-left: 20px"></h3>
        </div>  
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
                                                <th>Partidass</th>
                                                <th>Detalle</th>
                                                <th>Unidad Medida</th>
                                                <th>Cantidad Solicitada</th>
                                                <th>Cantidad Entregada</th>
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

        <!-- {!! Form::open(['route' => 'bandeja_rechazar_solicitud', 'method' => 'POST']) !!}
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
                            <div class="form-group">
                                <label>Motivo del rechazo</label>
                                <textarea name="observacion" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Autorizar</button>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close()!!}   -->
       

        

        
    @stop

    @section('css')
    <style>
        .select2-container {
            width: 100% !important;
        }
        /* CSS to style Treeview menu  */
        ol.tree {
                    padding: 0 0 0 30px;
                    /* width: 500px; */
            }
            .li { 
                    position: relative; 
                    margin-left: -15px;
                    list-style: none;
            }      
            .li input {
                    position: absolute;
                    left: 0;
                    margin-left: 0;
                    opacity: 0;
                    z-index: 2;
                    cursor: pointer;
                    height: 1em;
                    width: 1em;
                    top: 0;
            }
            .li input + ol {
                    background: url({{asset("/images/treeview/toggle-small-expand.png")}}) 40px 0 no-repeat;
                    margin: -1.600em 0px 8px -44px; 
                    height: 1em;
            }
            .li input + ol > .li { 
                    display: none; 
                    margin-left: -14px !important; 
                    padding-left: 1px; 
            }
            .li label {
                    background: url({{asset("/images/treeview/default.png")}}) 15px 1px no-repeat;
                    cursor: pointer;
                    display: block;
                    padding-left: 37px;
            }
            .li input:checked + ol {
                    background: url({{asset("images/treeview/toggle-small.png")}}) 40px 5px no-repeat;
                    margin: -1.96em 0 0 -44px; 
                    padding: 1.563em 0 0 80px;
                    height: auto;
            }
            .li input:checked + ol > .li { 
                    display: block; 
                    margin: 8px 0px 0px 0.125em;
            }
            .li input:checked + ol > .li:last-child { 
                    margin: 8px 0 0.063em;
            }
    </style>
    @endsection

    @section('javascript')
 
    @stop



@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
