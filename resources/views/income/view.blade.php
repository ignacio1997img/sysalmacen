@extends('voyager::master')

@section('page_title', 'Ver Ingresos')


    @section('page_header')
    <div class="col-md-12" style="padding: 10px;">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="javascript:;">
                <i class="voyager-credit-cards"></i> Viendo Ingreso
            </a>
            <div class="container-fluid">
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ route('income.index') }}"> <span class="glyphicon glyphicon-list"></span>&nbsp;Volver a la lista</a></li>
                       
                        <!-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Imprimir <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="" target="_blank">
                                        <span class="glyphicon glyphicon-print"></span>&nbsp;
                                            Imprimir Comprobante
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="" target="_blank">
                                        <span class="glyphicon glyphicon-print"></span>&nbsp;
                                            Imprimir Hoda de Ruta
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                   
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>    
    @stop

    @section('content')
        <div class="page-content read container-fluid div-phone">
            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Sucursal</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$sucursal->nombre}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-5">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Unidad Administrativa</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    {{$unidad[0]->Nombre}}
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-4">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Modalidad Compra + Numero Solicitud</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$modalidad->nombre}} - {{$sol->nrosolicitud}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Fecha Ingreso</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{\Carbon\Carbon::parse($sol->fechaingreso)->format('d/m/Y')}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-4">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Gestion</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$sol->gestion}}</p>                                    
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Factura</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$factura[0]->tipofactura}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-2">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Fecha Factura</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{\Carbon\Carbon::parse($factura[0]->fechafactura)->format('d/m/Y')}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-2">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Monto Factura</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$factura[0]->montofactura}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-2">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Nro Factura</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{$factura[0]->nrofactura}}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            @if($factura[0]->tipofactura == "Electronica")
                                <div class="col-md-2">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Nro Aut. Factura</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p>{{$factura[0]->nroautorizacion}}</p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-2">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Nro Control</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p>{{$factura[0]->nrocontrol}}</p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="panel-title">Archivos</h3>
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
                                                <!-- <th>&deg;</th> -->
                                                <th>Partida</th>
                                                <th>Articulo</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detalle as $data)
                                                <tr>
                                                    <td>{{$data->partida}}</td>
                                                    <td>{{$data->articulo}}</td>
                                                    <td>{{$data->cantidad}}</td>
                                                    <td>{{$data->precio}}</td>
                                                    <td>{{$data->cantidad * $data->precio}}</td>
                                                </tr>
                                                <?php
                                                    $total+= $data->cantidad * $data->precio;
                                                ?>
                                            @endforeach                                            
                                        </tbody>
                                        <tfoot>
                                            <th colspan="4" style="text-align:right"><h5>TOTAL</h5></th>
                                            <th><h4 id="total">Bs. {{$total}}</h4></th>
                                        </tfoot>
                                    </table>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- anulación modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="anular_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular la siguiente derivación?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form id="anulacion_form" action="" method="POST">
                            @csrf
                            <input type="hidden" name="entrada_id" value="">
                            <input type="hidden" name="id">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, anular">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete file modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="delete-file-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el archivo?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form id="delete_file_form" action="" method="POST">
                            @csrf
                            <input type="hidden" name="entrada_id" value="">
                            <input type="hidden" name="id">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        

        
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

