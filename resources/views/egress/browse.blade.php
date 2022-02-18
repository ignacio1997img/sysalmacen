@extends('voyager::master')

@section('page_title', 'Viendo Egresos')

@if(auth()->user()->hasPermission('browse_egres'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="c"> -->
                    <h1 class="page-title">
                        <i class="voyager-basket"></i> Egresos
                    </h1>
                    <!-- <a href="{{ route('egres.create') }}" class="btn btn-success btn-add-new">   en proceso no habilitar
                        <i class="voyager-plus"></i> <span>Crear</span>
                    </a> -->
                <!-- </div> -->
            </div>
        </div>
    @stop

    @section('content')
        <div class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#home">Realizados</a></li>
                                    <li><a data-toggle="tab" href="#pendiente">Pendientes</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">
                                        <div class="table-responsive text-center">
                                            <table class="dataTable table-hover">
                                                <thead>
                                                    <tr >
                                                        <th class="text-center">Nro</th>
                                                        <th class="text-center">Unidad Administrativa</th>
                                                        <th class="text-center">De</th>
                                                        <th class="text-center">Aprobado Por</th>
                                                        <th class="text-center">Nro Proceso</th>
                                                        <th class="text-center">Fecha Aprobacion</th>
                                                        <th class="text-center">Estado</th>
                                                        @if(auth()->user()->hasPermission('read_egres'))
                                                            <th>Accion</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i=1;
                                                    ?>
                                                    @foreach($realizado as $item)
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$item->unidad}}</td>
                                                            <td>{{$item->de}}</td>
                                                            <td>{{$item->aprobado}}</td>
                                                            <td>{{$item->nroproceso}}</td>
                                                            <td>{{date('d/m/Y', strtotime($item->fechapr))}}</td>
                                                            <td><small class="label label-success"><b>Entregado</b></small></td>
                                                            @if(auth()->user()->hasPermission('read_egres'))
                                                                <td>
                                                                    <a href="{{route('egreso_view_entregado',$item->solicitud_id)}}" target="_blank" title="Ver" class="btn btn-sm btn-info view">
                                                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <?php
                                                            $i++;
                                                        ?>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="pendiente" class="tab-pane fade">
                                        <div class="table-responsive text-center">
                                            <table class="dataTables table-hover">
                                                <thead>
                                                    <tr >
                                                        <th class="text-center">Nro</th>
                                                        <th class="text-center">Unidad Administrativa</th>
                                                        <th class="text-center">De</th>
                                                        <th class="text-center">Aprobado Por</th>
                                                        <th class="text-center">Nro Proceso</th>
                                                        <th class="text-center">Fecha Aprobacion</th>
                                                        <th class="text-center">Estado</th>
                                                        @if(auth()->user()->hasPermission('read_egres'))
                                                            <th>Accion</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i=1;
                                                    ?>
                                                    @foreach($pendiente as $item)
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$item->unidad}}</td>
                                                            <td>{{$item->de}}</td>
                                                            <td>{{$item->aprobado}}</td>
                                                            <td>{{$item->nroproceso}}</td>
                                                            <td>{{date('d/m/Y', strtotime($item->fechapr))}}</td>
                                                            <td><small class="label label-warning"><b>Pendiente</b></small></td>
                                                            @if(auth()->user()->hasPermission('read_egres'))
                                                                <td>
                                                                    <a href="{{route('egres_view_pendiente',$item->solicitud_id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <?php
                                                            $i++;
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
            </div>
        </div>
        <!-- <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'egres_delete', 'method' => 'DELETE']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente ingreso?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea eliminar el siguiente registro?</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
         -->
    @stop


    @section('css')

    @stop

    @section('javascript')
    <script>
                $(document).ready(function(){
                    $('.dataTable').DataTable({
                        language: {
                            // "order": [[ 0, "desc" ]],
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                            sSearch: "Buscar:",
                            sInfoThousands: ",",
                            sLoadingRecords: "Cargando...",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior"
                            },
                            oAria: {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            buttons: {
                                copy: "Copiar",
                                colvis: "Visibilidad"
                            }
                        },
                        order: [[ 0, 'desc' ]],
                    })
                });
                $(document).ready(function(){
                    $('.dataTables').DataTable({
                        language: {
                            // "order": [[ 0, "desc" ]],
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                            sSearch: "Buscar:",
                            sInfoThousands: ",",
                            sLoadingRecords: "Cargando...",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior"
                            },
                            oAria: {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            buttons: {
                                copy: "Copiar",
                                colvis: "Visibilidad"
                            }
                        },
                        order: [[ 0, 'desc' ]],
                    })
                });


                // $('#myModalEliminar').on('show.bs.modal', function (event) {
                //     var button = $(event.relatedTarget) //captura valor del data-empresa=""

                //     var id = button.data('id')

                //     var modal = $(this)
                //     modal.find('.modal-body #id').val(id)
                    
                // });

            </script>
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif

