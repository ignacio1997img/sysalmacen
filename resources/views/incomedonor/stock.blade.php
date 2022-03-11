@extends('voyager::master')

@section('page_title', 'Viendo Ingresos Donacion')

@if(auth()->user()->hasPermission('browse_solicituddonor'))
    @section('page_header')
        <!-- <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="voyager-basket"></i> Ingresos de Donaciones
                    </h1>
                    @if(auth()->user()->hasPermission('add_incomedonor'))
                        <a href="{{ route('incomedonor.create') }}" class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Crear</span>
                        </a>
                    @endif
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div> -->
    @stop

    @section('content')
            <div class="page-content browse container-fluid">
                @include('voyager::alerts')
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="panel panel-bordered">
                        <h2><b>Stock Disponible</b></h2>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="dataTable" class="dataTable table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nro&deg;</th>
                                                <th>Categoria</th>
                                                <th>Articulo</th>
                                                <th>Presentacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; ?>
                                                                                                                   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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


                

            </script>
    @stop


@else
    @section('content')
        <h1>No tienes permiso</h1>
        <br>
        <h1>Contactese con el Administrador del sistema</h1>
    @stop
@endif
