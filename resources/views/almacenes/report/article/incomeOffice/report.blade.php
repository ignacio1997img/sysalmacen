@extends('voyager::master')

@section('page_title', 'Reporte de stock disponible')
@if(auth()->user()->hasPermission('browse_printalmacen-article-incomeoffice'))


@section('page_header')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-6" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-calendar"></i> Reporte De Ingreso Por Oficinas
                            </h1>
                        </div>
                        <div class="col-md-6" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('almacen-article-incomeOffice.list') }}" method="POST">
                                @csrf
                                <input type="hidden" name="print">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="sucursal_id" id="sucursal_id" class="form-control select2" required>
                                            <option value=""disabled selected>Seleccione una opcion..</option>
                                            @foreach ($sucursal as $item)
                                                <option value="{{$item->sucursal->id}}">{{$item->sucursal->nombre}}</option>
                                            @endforeach                                             
                                        </select>
                                        <small>Sucursal</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="direccion_id" id="direccion_id"class="form-control select2" required>
                                            {{-- <option value=""disabled selected>Seleccione una opcion..</option>
                                            @foreach ($sucursal as $item)
                                                <option value="{{$item->sucursal->id}}">{{$item->sucursal->nombre}}</option>
                                            @endforeach                                              --}}
                                        </select>
                                        <small>Direciones Administrativa</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="unidad_id" id="unidad_id" class="form-control select2" required>
                                            {{-- <option value=""disabled selected>Seleccione una opcion..</option>
                                            @foreach ($sucursal as $item)
                                                <option value="{{$item->sucursal->id}}">{{$item->sucursal->nombre}}</option>
                                            @endforeach                                              --}}
                                        </select>
                                        <small>Unidades Administrativa</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="start" class="form-control" required>
                                                <small>Desde</small>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="finish" class="form-control" required>
                                                <small>Hasta</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div id="div-results" style="min-height: 100px">
                
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        a{
        text-decoration: none;
        }

        .main-wrap {
            background: #000;
                text-align: center;
        }
        .main-wrap h1 {
                color: #fff;
                    margin-top: 50px;
            margin-bottom: 100px;
        }
        .col-md-3 {
            display: block;
            float:left;
            margin: 1% 0 1% 1.6%;
            background-color: #eee;
        padding: 50px 0;
        }

        .col:first-of-type {
            margin-left: 0;
        }


        /* ALL LOADERS */

        .loader{
            width: 100px;
            height: 100px;
            border-radius: 100%;
            position: relative;
            margin: 0 auto;
        }
        /* LOADER 3 */

        #loader-3:before, #loader-3:after{
            content: "";
            width: 20px;
            height: 20px;
            position: absolute;
            top: 0;
            left: calc(50% - 10px);
            background-color: #3498db;
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
    <script>
        $(function()
        {
            $('#sucursal_id').on('change', onselect_direccion);
            $('#direccion_id').on('change', onselect_unidad);
        })
        
        function onselect_direccion()
        {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax-incomeOffice.direccion')}}/'+id, function(data){
                        var html_direcicion=    '<option value="" disabled selected>Seleccione una dirección..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_direcicion += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#direccion_id').html(html_direcicion);           
                    });
                }
                else
                {
                    $("#direccion_id").html('');
                    $("#unidad_id").html('');
                }
        }
        function onselect_unidad()
        {
                var id =  $(this).val();                   
                if(id >=1)
                {
                    $.get('{{route('ajax-incomeOffice.unidad')}}/'+id, function(data){
                        var html_unidad=    '<option value="" disabled selected>Seleccione una unidad..</option>'
                            html_unidad +=    '<option value="TODO">Todas las Unidades</option>'
                            for(var i=0; i<data.length; ++i)
                            html_unidad += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#unidad_id').html(html_unidad);           
                    });
                }
                else
                {
                    // alert(1)
                    $("#unidad_id").html('');
                }
        }


        $(document).ready(function() {

            $('#form-search').on('submit', function(e){
                
                e.preventDefault();
                $('#div-results').empty();
                // alert('Generando Reporte...');

                // $('#div-results').html('<div class="loading"><img src="https://w7.pngwing.com/pngs/477/964/png-transparent-wait-load" alt="loading" /><br/>Un momento, por favor...</div>');
                // $('#div-results').html('<div class="loader"></div>');
                var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
                $('#div-results').html(loader);
                // $('#div-results').loading({message: 'Cargando...'});
                $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                    $('#div-results').html(res);
                })
                .fail(function() {
                    toastr.error('Ocurrió un error!', 'Oops!');
                })
                .always(function() {
                    $('#div-results').loading('toggle');
                    $('html, body').animate({
                        scrollTop: $("#div-results").offset().top - 70
                    }, 500);
                });
            });
        });

        function report_print(){
            $('#form-search').attr('target', '_blank');
            $('#form-search input[name="print"]').val(1);
            window.form_search.submit();
            $('#form-search').removeAttr('target');
            $('#form-search input[name="print"]').val('');
        }
        function report_excel()
        {
            // $('#form-search').attr('target', '_blank');
            $('#form-search input[name="print"]').val(2);
            window.form_search.submit();
             $('#form-search').removeAttr('target');
            $('#form-search input[name="print"]').val('');
        }
    </script>
@stop
@else
    @section('content')
        @include('errors.403')
    @stop
@endif