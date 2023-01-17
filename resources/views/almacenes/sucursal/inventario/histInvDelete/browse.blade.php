@extends('voyager::master')

@section('page_title', 'Viendo Inventario')

@if(auth()->user()->hasPermission('browse_inventory'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 id="subtitle" class="page-title">
                        <i class="fa-solid fa-clock-rotate-left"></i> Historial
                    </h1>
                </div>
                <div class="col-md-4" style="text-align: right">
                    <a href="{{route('inventory.index', ['id'=>$sucursal_id])}}" data-toggle="modal" class="btn btn-warning btn-add-new">
                        <i class="fa-solid fa-circle-left"></i> <span>Volver</span>
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
                                        <tr >
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Gestion</th>
                                            <th class="text-center">Fecha Inicio</th>
                                            <th class="text-center">Fecha Finalizacion</th>
                                            <th class="text-center">Cancelado Por</th>
                                            <th class="text-center">Observación</th>
                                            {{-- <th style="text-align: center"></th> --}}
                                            <th style="text-align: right">Archivo</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i=1;
                                            $count =1;
                                            // dd(count($data));
                                        ?>
                                        @foreach($hist as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td style="text-align: center">{{$item->gestion}}</td>
                                                <td style="text-align: center">{{date('d/m/Y', strtotime($item->start))}}</td>
                                                <td style="text-align: center">{{$item->finish?date('d/m/Y', strtotime($item->finish)):'Sin Fecha'}}</td>
                                                <td style="text-align: center">{{$item->user->name}}</td>
                                                <td>{{$item->deleteObservation}}</td>

                                                @php
                                                    // dd($item->histDetalleFactura->sum('precio * cantrestante'));
                                                @endphp
                                                {{-- <td style="text-align: right">{{$item->histDetalleFactura->sum('cantrestante')}}</td> --}}


                                                <td style="text-align: center">
                                                    <div class="no-sort no-click bread-actions text-right">
                                                        <a href="{{ url('storage/'.$item->routeFile) }}" class="btn btn-sm btn-info" target="_blank" title="Historial de reabrir gestión" class="btn btn-sm btn-dark view">
                                                            <i class="fa-solid fa-file"></i> <span class="hidden-xs hidden-sm">Ver Archivo</span>
                                                        </a>
                                                    </div>
                                                </td>
                                                            
                                            </tr>
                                            <?php
                                                $i++;
                                                $count++;
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
    
    @stop


    @section('css')
    <style>
        #subtitle{
            font-size: 18px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }
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
        small{font-size: 12px;
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

                $('#modal_lock').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget) //captura valor del data-empresa=""

                    var id = button.data('id')

                    var modal = $(this)
                    modal.find('.modal-body #id').val(id)
                    
                });
                $('#modal_lock_open').on('show.bs.modal', function (event) {
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
