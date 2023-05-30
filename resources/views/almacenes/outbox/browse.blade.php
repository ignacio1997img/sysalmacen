@extends('voyager::master')

@section('page_title', 'Viendo Solicitudes de Pedidos')

@if(auth()->user()->hasPermission('browse_outbox'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="c"> -->
                    <h1 id="subtitle" class="page-title">
                        <i class="fa-solid fa-cart-shopping"></i> Pedidos
                    </h1>
                {{-- @if(auth()->user()->hasPermission('add_egres')) --}}
                    <a href="{{ route('outbox.create') }}" class="btn btn-success btn-add-new">
                        <i class="voyager-plus"></i> <span>Crear</span>
                    </a>
                {{-- @endif --}}
                <!-- </div> -->
            </div>
        </div>
    @stop
    @section('content')
        <div class="page-content browse container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="dataTables_length" id="dataTable_length">
                                        <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> registros</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="input-search" class="form-control">
                                </div>
                            </div>
                            <div class="row" id="div-results" style="min-height: 120px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-success fade" tabindex="-1" id="myModalEnviar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'outbox.enviar', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-file"></i> Solicitud Pedido</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="fa-solid fa-file" style="color: rgb(134, 127, 127); font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea enviar la solicitud de pedidos?</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, enviar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
        <div class="modal modal-success fade" tabindex="-1" id="myModalConfirmarEliminacion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'outbox.enviar', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-check"></i> Confirmar Eliminación</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Aviso: </strong>
                            <p>El confirmar la eliminacion, usted debera devolver todos el detalle o artículo al encargado del almacen.</p>
                        </div> 
                        <input type="hidden" name="id" id="id">

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="fa-solid fa-check" style="color: rgb(134, 127, 127); font-size: 5em;"></i>
                            <br>
                            <br>
                            
                            <p><b>Desea confirmar la elminación?</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, confirmar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
        <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'outbox.deletepedido', 'method' => 'post']) !!}
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

        /* LOADER 3 */
        
        #loader-3:before, #loader-3:after{
        content: "";
        width: 20px;
        height: 20px;
        position: absolute;
        top: 0;
        left: calc(50% - 10px);
        background-color: #5eaf4a;
        animation: squaremove 1s ease-in-out infinite;
        }
        
        #loader-3:after{
        bottom: 0;
        animation-delay: 0.5s;
        }
        
        @keyframes squaremove{
        0%, 100%{
            -webkit-transform: translate(0,0) rotate(0);
            -ms-transform: translate(0,0) rotate(0);
            -o-transform: translate(0,0) rotate(0);
            transform: translate(0,0) rotate(0);
        }
        
        25%{
            -webkit-transform: translate(40px,40px) rotate(45deg);
            -ms-transform: translate(40px,40px) rotate(45deg);
            -o-transform: translate(40px,40px) rotate(45deg);
            transform: translate(40px,40px) rotate(45deg);
        }
        
        50%{
            -webkit-transform: translate(0px,80px) rotate(0deg);
            -ms-transform: translate(0px,80px) rotate(0deg);
            -o-transform: translate(0px,80px) rotate(0deg);
            transform: translate(0px,80px) rotate(0deg);
        }
        
        75%{
            -webkit-transform: translate(-40px,40px) rotate(45deg);
            -ms-transform: translate(-40px,40px) rotate(45deg);
            -o-transform: translate(-40px,40px) rotate(45deg);
            transform: translate(-40px,40px) rotate(45deg);
        }
        }
        
        
        </style>
    @stop

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
            
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
        <script>
            var countPage = 10, order = 'id', typeOrder = 'desc';
            $(document).ready(() => {
                list();
                
                $('#input-search').on('keyup', function(e){
                    if(e.keyCode == 13) {
                        list();
                    }
                });

                $('#select-paginate').change(function(){
                    countPage = $(this).val();
                
                    list();
                });
            });

            function list(page = 1){
                // $('#div-results').loading({message: 'Cargando...'});
                var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
                $('#div-results').html(loader);

                // let type = $(".radio-type:checked").val();

                let url = '{{ url("admin/outbox/ajax/list") }}';
                let search = $('#input-search').val() ? $('#input-search').val() : '';

                $.ajax({
                    url: `${url}/${search}?paginate=${countPage}&page=${page}`,

                    type: 'get',
                    
                    success: function(result){
                    $("#div-results").html(result);
                }});

            }

            $('#myModalEnviar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)

                var id = button.data('id')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                    
            });
            $('#myModalConfirmarEliminacion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)

                var id = button.data('id')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                    
            });

            $('#myModalEliminar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)

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