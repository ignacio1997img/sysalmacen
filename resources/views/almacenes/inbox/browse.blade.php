@extends('voyager::master')

@section('page_title', 'Viendo Solicitudes de los pedido por aprobar')

@if(auth()->user()->hasPermission('browse_inbox'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="c"> -->
                    <h1 id="subtitle" class="page-title">
                        <i class="icon fa-regular fa-clipboard"></i> Solicitudes
                    </h1>
        
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
                                <div class="col-sm-12 text-right">
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="todo">Todos</label>
                                
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pendiente" checked>Pendientes</label>

                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="aprobado">Aprobados</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="entregado">Entregados</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="rechazado">Rechazados</label>
                                                                    
                                </div>
                            </div>
                            <div class="row" id="div-results" style="min-height: 120px"></div>
                        </div>
                    </div>
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
                $('.radio-type').click(function(){
                    list();
                });
                    
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

                let type = $(".radio-type:checked").val();
                // alert(type);

                let url = '{{ url("admin/inbox/ajax/list") }}';
                let search = $('#input-search').val() ? $('#input-search').val() : '';

                $.ajax({
                    url: `${url}/${type}/${search}?paginate=${countPage}&page=${page}`,

                    type: 'get',
                    
                    success: function(result){
                    $("#div-results").html(result);
                }});

            }

            
            
        
        </script>
    @stop
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif