@extends('voyager::master')

@section('page_title', 'Viendo Solicitud Pedido')

@if(auth()->user()->hasPermission('browse_solicitud'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="voyager-basket"></i> Solicitudes De Egresos
                    </h1>
                    @if(auth()->user()->hasPermission('add_solicitud'))
                        @if($cant == 0)
                            <a href="{{ route('incomesolicitud.create') }}" class="btn btn-success btn-add-new">
                                <i class="voyager-plus"></i> <span>Ingreso</span>
                            </a>
                            <a href="{{ route('solicitud.create') }}" class="btn btn-success btn-add-new">
                                <i class="voyager-plus"></i> <span>Egreso</span>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-md-4">

                </div>
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
                                        <li class="active"><a data-toggle="tab" href="#home">Ingreso</a></li>
                                        <li><a data-toggle="tab" href="#egreso">Egreso</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            <div class="table-responsive text-center">
                                                <table id="dataTable" class="dataTable table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Nrossss&deg;</th>
                                                            <th>Nro Proceso </th>
                                                            <th>Fecha Solicitud</th>
                                                            <th>Estado</th>
                                                            <th class="no-sort no-click bread-actions text-right">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($solicitud as $data)  
                                                            <tr>
                                                                <td>{{$data->id}}</td>
                                                                <td>{{$data->nroproceso}}</td>
                                                                <td>{{$data->fechasolicitud}}</td>
                                                                <td>
                    
                                                                    @if ($data->estado == "Creado")
                                                                        <label class="label label-warning">{{$data->estado}}</label>
                                                                    @endif

                                                                    @if ($data->estado == "Derivado")
                                                                        <label class="label label-primary">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Rechazado")
                                                                        <label class="label label-danger">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Aprobado")
                                                                        <label class="label label-info">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Entregado")
                                                                        <label class="label label-success">Solicitud Recibida</label>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="no-sort no-click bread-actions text-right">
                                                                        @if(auth()->user()->hasPermission('read_solicitud'))  
                                                                            <a href="{{route('solicitudes_view', $data->id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                            </a>
                                                                        @endif 
                                                                        @if ($data->estado == "Creado")
                                                                            @if(auth()->user()->hasPermission('add_solicitud'))                                                              
                                                                                <a type="button" data-toggle="modal" data-target="#derivar_modal" data-id="{{$data->id}}" class="btn-sm btn-success"><i class="voyager-move"></i> <span class="hidden-xs hidden-sm">Derivar</span></a>
                                                                            @endif
                                                                            @if(auth()->user()->hasPermission('edit_solicitud'))                                                                                                                      
                                                                                <a type="button" data-toggle="modal" data-target="#delete_editar" class="btn-sm btn-success"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span></a>
                                                                            @endif 
                                                                        @endif  
                                                                        @if(auth()->user()->hasPermission('delete_solicitud'))  
                                                                            @if ($data->estado != "Entregado" and $data->estado != "Rechazado")
                                                                                <a type="button" data-toggle="modal" data-target="#delete_modal" data-id="{{$data->id}}" data-numero="{{$data->nroproceso}}" class="btn-sm btn-danger"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span></a>
                                                                            @endif 
                                                                        @endif 
                                                                    </div>      
                                                                </td>
                                                            </tr>                                    
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="egreso" class="tab-pane fade">
                                            <div class="table-responsive text-center">
                                                <table id="dataTable" class="dataTable table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Nro&deg;</th>
                                                            <th>Nro Proceso </th>
                                                            <th>Fecha Solicitud</th>
                                                            <th>Estado</th>
                                                            <th class="no-sort no-click bread-actions text-right">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($solicitud as $data)  
                                                            <tr>
                                                                <td>{{$data->id}}</td>
                                                                <td>{{$data->nroproceso}}</td>
                                                                <td>{{$data->fechasolicitud}}</td>
                                                                <td>
                    
                                                                    @if ($data->estado == "Creado")
                                                                        <label class="label label-warning">{{$data->estado}}</label>
                                                                    @endif

                                                                    @if ($data->estado == "Derivado")
                                                                        <label class="label label-primary">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Rechazado")
                                                                        <label class="label label-danger">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Aprobado")
                                                                        <label class="label label-info">{{$data->estado}}</label>
                                                                    @endif
                                                                    @if ($data->estado == "Entregado")
                                                                        <label class="label label-success">Solicitud Recibida</label>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="no-sort no-click bread-actions text-right">
                                                                        @if(auth()->user()->hasPermission('read_solicitud'))  
                                                                            <a href="{{route('solicitudes_view', $data->id)}}" title="Ver" class="btn btn-sm btn-info view">
                                                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                            </a>
                                                                        @endif 
                                                                        @if ($data->estado == "Creado")
                                                                            @if(auth()->user()->hasPermission('add_solicitud'))                                                              
                                                                                <a type="button" data-toggle="modal" data-target="#derivar_modal" data-id="{{$data->id}}" class="btn-sm btn-success"><i class="voyager-move"></i> <span class="hidden-xs hidden-sm">Derivar</span></a>
                                                                            @endif
                                                                            @if(auth()->user()->hasPermission('edit_solicitud'))                                                                                                                      
                                                                                <a type="button" data-toggle="modal" data-target="#delete_editar" class="btn-sm btn-success"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span></a>
                                                                            @endif 
                                                                        @endif  
                                                                        @if(auth()->user()->hasPermission('delete_solicitud'))  
                                                                            @if ($data->estado != "Entregado" and $data->estado != "Rechazado")
                                                                                <a type="button" data-toggle="modal" data-target="#delete_modal" data-id="{{$data->id}}" data-numero="{{$data->nroproceso}}" class="btn-sm btn-danger"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span></a>
                                                                            @endif 
                                                                        @endif 
                                                                    </div>      
                                                                </td>
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
            </div>


            <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    {!! Form::open(['route' => 'solicitudes_delete', 'method' => 'DELETE']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea Eliminar la Siguiente Solicitud de Egreso</b></p>
                                <p><small><b><h4 id="numero"></h4></b></small></p>
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
            <div class="modal modal-success fade" tabindex="-1" id="derivar_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    {!! Form::open(['route' => 'derivar_proceso_solicitud', 'method' => 'POST']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-forward"></i> Derivar Solicitud De Pedido</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">

                            
                                <div class="form-group">
                                    <div class="form-line">
                                        <select id="dirigido" name="dirigido" class="form-control select2" required>
                                            <option value="">Seleccione una Opcion..</option>
                                            @foreach($usuarios as $data)
                                                <option value="{{$data->id}}">{{$data->funcionario}} - {{$data->unidad}}</option>
                                            @endforeach          
                                        </select>
                                        </div>
                                    <small>Seleccionar un Destinatario.</small>
                                </div>
                
                            <div class="form-group">
                                <label class="">Observaciones</label>
                                <textarea name="observacion" class="form-control" rows="5"></textarea>
                            </div>
                        </div>                
                        <div class="modal-footer">
                            
                                <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, Derivar">
                            
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                        </div>
                    {!! Form::close()!!} 
                    </div>
                </div>
            </div>
            
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

                $('#delete_modal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget) //captura valor del data-empresa=""

                    var id = button.data('id')
                    var numero ='Nro Proceso: '+ button.data('numero')

                    var modal = $(this)
                    modal.find('.modal-body #id').val(id)
                    modal.find('.modal-body #numero').text(numero)
                    
                });

                $('#derivar_modal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget) //captura valor del data-empresa=""

                    var id = button.data('id')
                

                    var modal = $(this)
                    modal.find('.modal-body #id').val(id)
                });
                

            </script>
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif