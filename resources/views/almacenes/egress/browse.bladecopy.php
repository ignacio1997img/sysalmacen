@extends('voyager::master')

@section('page_title', 'Viendo Egresos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-title">
                    <i class="voyager-basket"></i> Egresos
                </h1>
                <a href="{{ route('egres.create') }}" class="btn btn-success btn-add-new">
                    <i class="voyager-plus"></i> <span>Crear</span>
                </a>
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
                                            <th class="text-center">Nro&deg;</th>
                                            <th class="text-center">Nro&deg; Pedido</th>
                                            <th class="text-center">Fecha Solicitud</th>
                                            <th class="text-center">Fecha Salida</th>
                                            <th class="text-center">Oficina</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        $i=1;
                                    ?>
                                    <tbody>
                                        @foreach($egreso as $data)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td class="text-center">{{$data->nropedido}}</td>
                                                <td class="text-center">{{\Carbon\Carbon::parse($data->fechasolicitud)->format('d/m/Y')}}</td>
                                                <td class="text-center">{{date('d/m/Y H:i:s', strtotime($data->fechaegreso))}}</td>
                                                <td class="text-center">{{$data->unidad}}</td>
                                                <td>
                                                    <div class="no-sort no-click bread-actions text-right">
                                                        <a href="{{route('egres_view', $data->id)}}" title="Ver" target="_blank" class="btn btn-sm btn-info view">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                 
                                                
                                                            <button title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-id="{{$data->id}}" data-target="#myModalEliminar">
                                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                                            </button>    
                                                                                             
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
        <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
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
                        
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="S??, eliminar">
                        
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
                        sEmptyTable: "Ning??n dato disponible en esta tabla",
                        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                        sSearch: "Buscar:",
                        sInfoThousands: ",",
                        sLoadingRecords: "Cargando...",
                        oPaginate: {
                            sFirst: "Primero",
                            sLast: "??ltimo",
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
