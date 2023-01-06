@extends('voyager::master')

@section('page_title', 'Viendo Inventario')

@if(auth()->user()->hasPermission('browse_inventory'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 id="subtitle" class="page-title">
                        <i class="voyager-data"></i> Inventario
                    </h1>
                    @if(auth()->user()->hasPermission('start_inventory') && count($ok)==0)
                        <a href="#" data-toggle="modal" data-target="#modal_start" class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Nueva Gestion</span>
                        </a>
                    @endif
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
                                            <th class="text-center">Estado</th>
                                            <th>Accion</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i=1;
                                        ?>
                                        @foreach($data as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td style="text-align: center">{{$item->gestion}}</td>
                                                <td style="text-align: center">{{date('d/m/Y', strtotime($item->start))}}</td>
                                                <td style="text-align: center">{{$item->finish?date('d/m/Y', strtotime($item->finish)):'Sin Fecha'}}</td>
                                                <td style="text-align: center">
                                                    @if ($item->status == 1)
                                                        <label class="label label-primary">ABIERTA</label>
                                                    @else
                                                        <label class="label label-dark">CERRADA</label>
                                                    @endif
                                                </td>

                                                <td style="text-align: center">
                                                    <div class="no-sort no-click bread-actions text-right">
                                                        @if ($item->status == 1)
                                                            <a data-toggle="modal" data-id="{{$item->id}}" data-target="#modal_lock" title="Eliminar" class="btn btn-sm btn-danger view">
                                                                <i class="fa-solid fa-lock"></i> <span class="hidden-xs hidden-sm">Cerrar Gestion</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                                            
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
        <div class="modal modal-danger fade" tabindex="-1" id="modal_lock" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'inventory.finish', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-lock"></i> Desea cerrar gestion?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="sucursal_id" value="{{$sucursal->id}}">

                        <input type="hidden" name="id" id="id">
                        <div class="alert alert-warning">
                            <strong>Advertencia:</strong>
                            <p>Una ves cerrada la gestion actual, se cerrara para todos los almacenes y no se podra re abrir la gestion que haya sido cerrada.</p>
                        </div>

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="fa-solid fa-lock" style="color: red; font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea cerrar gestion?</b></p>
                        </div>
                        {{-- <div class="row"> --}}
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        {{-- <input type="date" name="fechaingreso" class="form-control text" required> --}}
                                        <textarea name="observation1" id="" cols="30" rows="2" class="form-control text" required></textarea>
                                    </div>
                                    <small>Observación.</small>
                                </div>
                            </div>
                        
                        {{-- </div> --}}
                    </div>                
                    <div class="modal-footer">                        
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, cerrar">                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>

        <div class="modal modal-success fade" tabindex="-1" id="modal_start" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'inventory.start', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-lock-open"></i> Desea abrir nueva gestión?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center" style="text-transform:uppercase">
                            <i class="fa-solid fa-lock-open" style="color: #43d17f; font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea abrir nueva gestión?</b></p>
                        </div>
                        <input type="hidden" name="sucursal_id" value="{{$sucursal->id}}">
                        {{-- <div class="row"> --}}
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control text" disabled value="{{$max+1}}">
                                        <input type="hidden" name="gestion" class="form-control text" value="{{$max+1}}" required>
                                    </div>
                                    <small>Gestión.</small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        {{-- <input type="date" name="fechaingreso" class="form-control text" required> --}}
                                        <textarea name="observation" id="" cols="30" rows="2" class="form-control text" required></textarea>
                                    </div>
                                    <small>Observación.</small>
                                </div>
                            </div>
                        
                        {{-- </div> --}}
                    </div>                
                    <div class="modal-footer">                        
                        <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, abrir">                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
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



            </script>
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
