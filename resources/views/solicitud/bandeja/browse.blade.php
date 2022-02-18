@extends('voyager::master')

@section('page_title', 'Bandeja de Entradas')

@if(auth()->user()->hasPermission('browse_bandeja'))

    @section('content')
        <div class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">

                            <div class="alert alert-info" style="padding: 5px 10px; display: none" role="alert" id="alert-new">
                                <div class="row">
                                    <div class="col-md-8" style="margin: 0px">
                                    <p style="margin-top: 10px"><b>Atención!</b> Tienes una nueva derivación, refresca la página para actualizar la lista.</p></div>
                                    <div class="col-md-4 text-right" style="margin: 0px"><button class="btn btn-default">Refrescar <i class="voyager-refresh"></i></button></div>
                                </div>
                            </div>

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">Pendientes</a></li>
                                <li><a data-toggle="tab" href="#urgente">Rechazados</a></li>
                                <li><a data-toggle="tab" href="#archivados">Aprobados</a></li>
                            </ul>
                            
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="table-responsive text-center">
                                        <table class="dataTable table-hover">
                                            <thead>
                                                <tr >
                                                    <th class="text-center">ID</th>
                                                    <th class="text-center">De</th>
                                                    <th class="text-center">Unidad Administrativa</th>
                                                    <th class="text-center">Nro Proceso</th>
                                                    <th class="text-center">Fecha Envio</th>
                                                    @if(auth()->user()->hasPermission('read_bandeja'))
                                                        <th>Accion</th>
                                                    @endif

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendientes as $data)
                                                    <tr>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->de_nombre}}</td>
                                                        <td>{{$data->unidad}}</td>
                                                        <td>{{$data->nroproceso}}</td>
                                                        <td>{{date('d/m/Y H:i:s', strtotime($data->created_at))}}<br><small>{{\Carbon\Carbon::parse($data->created_at)->diffForHumans()}}</td>
                                                        @if(auth()->user()->hasPermission('read_bandeja'))
                                                        <td>
                                                            <a href="{{route('bandeja_pendiente_view', $data->solicitud_id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                            </a>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="urgente" class="tab-pane fade">
                                    <div class="table-responsive">
                                        <table class="dataTable table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>De</th>
                                                    <th>Unidad Administrativa</th>
                                                    <th>Nro Proceso</th>
                                                    <th>Fecha Envio</th>
                                                    @if(auth()->user()->hasPermission('read_bandeja'))
                                                        <th>Accion</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rechazado as $data)
                                                    <tr>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->de_nombre}}</td>
                                                        <td>{{$data->unidad}}</td>
                                                        <td>{{$data->nroproceso}}</td>
                                                        <td>{{date('d/m/Y H:i:s', strtotime($data->created_at))}}<br><small>{{\Carbon\Carbon::parse($data->created_at)->diffForHumans()}}</td>
                                                        @if(auth()->user()->hasPermission('read_bandeja'))
                                                            <td>
                                                                <a href="{{route('bandeja_pendiente_view', $data->solicitud_id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                </a>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="archivados" class="tab-pane fade">
                                    <div class="table-responsive">
                                        <table class="dataTable table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>De</th>
                                                    <th>Unidad Administrativa</th>
                                                    <th>Nro Proceso</th>
                                                    <th>Fecha Envio</th>
                                                    <th>Estado</th>
                                                    <th>Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($aprobado as $data)
                                                    <tr>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->de_nombre}}</td>
                                                        <td>{{$data->unidad}}</td>
                                                        <td>{{$data->nroproceso}}</td>
                                                        <td>{{date('d/m/Y H:i:s', strtotime($data->created_at))}}<br><small>{{\Carbon\Carbon::parse($data->created_at)->diffForHumans()}}</td>
                                                        <td>
                                                            @if($data->atendido == 1)
                                                                <small class="label label-success"><b>Solicitud Entregada</b></small>
                                                            @else
                                                                <small class="label label-warning"><b>Solicitud sin Entregar</b></small>
                                                            @endif
                                                        </td>
                                                        @if(auth()->user()->hasPermission('read_bandeja'))
                                                            <td>
                                                                <a href="{{route('bandeja_aprobada_view', $data->solicitud_id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                </a>
                                                            </td>
                                                        @endif
                                                    </tr>
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
    @stop

    @section('css')
        <style>
            .entrada:hover{
                cursor: pointer;
                opacity: .7;
            }
            .unread{
                background-color: rgba(9,132,41,0.2) !important
            }
        </style>
    @endsection

    @section('javascript')
        <script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>
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


        </script>
    @stop
    

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
