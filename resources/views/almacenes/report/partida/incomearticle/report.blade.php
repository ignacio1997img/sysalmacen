@extends('voyager::master')

@section('page_title', 'Reporte de ingreso por rango de facha')
@if(auth()->user()->hasPermission('browse_printalmacen-partida-incomearticle'))


@section('page_header')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-6" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-calendar"></i> Reporte De Ingresos de Articulos Por Partidas
                            </h1>
                        </div>
                        <div class="col-md-6" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('almacen-partida-incomearticle.list') }}" method="POST">
                                @csrf
                                <input type="hidden" name="print">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="sucursal_id" id="sucursal_id" class="form-control select2" required>
                                            <option value=""disabled selected>--Seleccione una opción--</option>
                                            @foreach ($sucursal as $item)
                                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                            @endforeach                                             
                                        </select>
                                        <small>Sucursal</small>
                                    </div>
                                    <br>
                                    <div class="form-line">
                                        <select name="type_id" id="type_id" class="form-control select2" required>        
                                        </select>
                                        <small>Tipo</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="partida_id" id="partida_id"class="form-control select2" required>
                                            <option value=""disabled selected>--Seleccione una opción</option>
                                            @foreach ($partida as $item)
                                                <option value="{{$item->id}}">{{$item->codigo}} - {{$item->nombre}}</option>
                                            @endforeach                                             
                                        </select>
                                        <small>Partidas</small>
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
    
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>    

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
        $('#sucursal_id').on('change',function()
        {
            id= $(this).val();
            if(id>=1)
            {
                $.get('{{route('ajax-sucursal-subalmacen.get')}}/'+id, function(data){
                    var html_type=    '<option disabled selected value="">-- Seleccione una gestión --</option>'
                        html_type +=    '<option value="TODO">Todos</option>'
                    for(var i=0; i<data.length; ++i)
                        html_type += '<option value="'+data[i].id+'">'+data[i].name+'</option>'

                    $('#type_id').html(html_type);
                });
            }
            else
            {
                $('#type_id').html('');
            }
        });
    </script>
@stop
@else
    @section('content')
        @include('errors.403')
    @stop
@endif