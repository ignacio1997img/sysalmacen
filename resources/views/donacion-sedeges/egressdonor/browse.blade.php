@extends('voyager::master')

@section('page_title', 'Viendo Ingresos Donacion')
@if(auth()->user()->hasPermission('browse_egressdonor'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title" id="subtitle">
                        <i class="voyager-basket"></i> Egreso de Donaciones
                    </h1>
                    @if(auth()->user()->hasPermission('add_egressdonor'))
                        <a href="{{ route('egressdonor.create') }}" class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Crear</span>
                        </a>
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
                                    <table id="dataTable" class="dataTable table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nro&deg;</th>
                                                <th>Nro&deg; Ingreso</th>
                                                <th>Centro</th>
                                                <th>Observacion</th>
                                                <th>Fecha Donacion</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($egreso as $data)
                                                <tr>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{$data->nrosolicitud}}</td>
                                                    <td style="width: 200pt">{{$data->nombre}}</td>
                                                    <td style="width: 200pt">{{$data->observacion}}</td>
                                                    <td>{{\Carbon\Carbon::parse($data->fechaentrega)->format('d/m/Y')}}</td>
                                                    <td>
                                                        <div class="no-sort no-click bread-actions text-right">
                                                            @if(auth()->user()->hasPermission('read_egressdonor'))
                                                                <a href="{{route('egressdonor_view_photo',$data->id)}}" title="Ver" class="btn btn-sm btn-success view">
                                                                    <i class="voyager-photos"></i> <span class="hidden-xs hidden-sm">Archivos</span>
                                                                </a>
                                                                <a href="{{route('egressdonor.show',$data->id)}}" title="Ver" target="_blank" class="btn btn-sm btn-info view">
                                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                </a>
                                                            @endif
                                                            <!-- @if($data->condicion == 1)
                                                                @if(auth()->user()->hasPermission('edit_egressdonor'))
                                                                    <a href="{{route('income.edit',$data->id)}}" title="Editar" class="btn btn-sm btn-warning">
                                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                                    </a>     
                                                                @endif
                                                                @if(auth()->user()->hasPermission('delete_egressdonor'))
                                                                    <button title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-id="{{$data->id}}" data-target="#myModalEliminar">
                                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                                                    </button>      
                                                                @endif
                                                            @endif -->
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
            <!-- Modal -->
            <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['route' => 'incomedonor_delete', 'method' => 'DELETE']) !!}
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
    @stop


    @section('css')
    <style>
        #dataTable {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #dataTable td, #dataTable th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #dataTable tr:nth-child(even){background-color: #f2f2f2;}

        #dataTable tr:hover {background-color: #ddd;}

        #dataTable th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
        
        #subtitle{
            font-size: 18px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }

    </style>
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


                $('#myModalEliminar').on('show.bs.modal', function (event) {
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
        <br>
        <h1>Contactese con el Administrador del sistema</h1>
    @stop
@endif
