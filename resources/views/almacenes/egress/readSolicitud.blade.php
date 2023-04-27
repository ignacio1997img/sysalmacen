@extends('voyager::master')

@section('page_title', 'Viendo Detalle Solicitud')

@section('page_header')
    
    <div class="container-fluid">
        <div class="row">
            <h1 id="subtitle" class="page-title">
                <i class="voyager-basket"></i> Detalle Solicitud
            </h1>
            <a href="{{ route('egres.index') }}" class="btn btn-warning btn-add-new">
                <i class="fa-solid fa-file"></i> <span>Volver</span>
            </a>
            @if ($data->status == 'Aprobado')
                <a data-toggle="modal" data-target="#modal-rechazar" title="Rechazar Solicitud" class="btn btn-sm btn-dark view">
                    <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Rechazar</span>
                </a> 
            @endif
            
        </div>
    </div>
@stop

@section('content')    
    <div id="app">
        <div class="page-content browse container-fluid" >            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">                                       
                            {{-- <h5 id="subtitle">Solicitud de Compras</h5> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Almacen</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{strtoupper($data->sucursal->nombre)}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Solicitante</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{strtoupper($data->first.' '.$data->last_name)}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Fecha de Solicitud</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{date('d/m/Y H:i:s', strtotime($data->fechasolicitud))}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Dirección</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$data->direccion_name}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Unidad</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$data->unidad_name}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                               
                            </div>
                            <h5 id="subtitle">Articulo:</h5>
                            <div class="row">
                                <table id="dataTableStyle" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px; text-align: center">N°</th>                                                 
                                            <th style="text-align: center">PARTIDA</th>
                                            <th style="text-align: center">DETALLE</th>
                                            <th style="text-align: center">UNIDAD</th>                
                                            <th style="text-align: center">CANT. SOLICITADA</th>                
                                            <th style="text-align: center; width: 200px">CANT. A ENTREGAR</th>                
                                        </tr>
                                    </thead>    
                                    <tbody>
                                        @php
                                            $numeroitems =1;
                                        @endphp
                                        @foreach ($data->solicitudDetalle as $item)
                                            <tr>
                                                <td style="text-align: right">{{$numeroitems}}</td>
                                                <td style="text-align: center">{{$item->article->partida->codigo}}</td>
                                                <td style="text-align: left">{{strtoupper($item->article->nombre)}}</td>
                                                <td style="text-align: center">{{strtoupper($item->article->presentacion)}}</td>
                                                <td style="text-align: right">{{number_format($item->cantsolicitada)}}</td>
                                                <td style="text-align: right">
                                                    <div class="no-sort no-click bread-actions text-right">
                                                        {{-- <input type="number" style="width: 80px; text-align: right" name="cantidad[]" value="{{number_format($item->cantsolicitada)}}"> --}}
                                                        {{-- <input type="number" name="cantidad[]" id="amount" min="0" step=".1" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" style="text-align: right; width: 100px; height: 36px" class="text" required>                                     --}}
                                              
                                                        <label class="label label-success" id="cantentregar-{{$item->id}}" style="font-size: 12px;">{{$item->cantentregada}}</label>
                                                        @if ($data->status != 'Entregado')                                                      
                                                            <a href="#" data-toggle="modal" data-target="#show-modal" 
                                                                data-detalle_id="{{$item->id}}"
                                                                data-article_id="{{$item->article_id}}"
                                                                data-article="{{strtoupper($item->article->nombre)}}"
                                                                data-partida="{{strtoupper($item->article->partida->codigo.'-'.$item->article->partida->nombre)}}"
                                                                data-cantidad="{{$item->cantsolicitada}}"
                                                                data-unidad_id="{{$data->unidad_id}}"
                                                                data-item='@json($item)' title="Ver" class="btn btn-sm btn-warning view">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                $numeroitems++;
                                            ?>
                                        @endforeach
                                    </tbody>                                        
                                </table>
                            </div>    
                            @if ($data->status != 'Entregado')
                                <a data-toggle="modal" data-target="#myModalEntregar" title="Aprobar Solicitud" class="btn btn-sm btn-success view">
                                    <i class="fa-solid fa-bag-shopping"></i> Entregar Pedido
                                </a>  
                            @endif                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
    

    {{-- Para rechazar la solicitud al momento de entregar el pedido --}}{{-- Modal para rechazar solicitud --}}
            <div class="modal fade" tabindex="-1" id="modal-rechazar" role="dialog">
                <div class="modal-dialog modal-dark">
                    <div class="modal-content">
                        {!! Form::open(['route' => 'egres.rechazar', 'method' => 'POST']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa-solid fa-thumbs-down"></i> Rechazar Solicitud</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="{{$data->id}}">
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-thumbs-down" style="color: #4d4c4b; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea rechazar la solicitud?</b></p>
                            </div>
                        </div>                
                        <div class="modal-footer">
                            
                                <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, rechazar">
                            
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                        </div>
                        {!! Form::close()!!} 
                    </div>
                </div>
            </div>

    {{-- Modal para ver los detalle del stock del producto de que unidad tiene --}}
    <form action="{{ route('egres-ajax.detalle.store') }}" id="form-create-customer" method="POST">
        {{ csrf_field() }}
        <div class="modal fade" tabindex="-1" id="show-modal" role="dialog">
            <div class="modal-dialog modal-warning modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-basket"></i> Detalles</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="detalle_id" id="detalles_id">
                        <div class="row">
                            <div class="col-md-6" style="margin-bottom:0;">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Partida</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <small id="label-partida">Value</small>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6" style="margin-bottom:0;">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Articulo</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <small id="label-article">Value</small>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6" style="margin-bottom:0;">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Cantidad Solicitada</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <small style="font-size: 15px" id="label-cantidad">Value</small>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            
                            <div class="col-md-12">
                                <table id="detalle" class="table table-bordered table-hover detalle">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-center">Solicitud Compra de la Unidad</th>
                                        </tr>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>ENTIDAD + NRO COMPRA</th>
                                            <th>PRECIO</th>
                                            <th>STOCK</th>
                                            <th width="5px">CANTIDAD</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            @if ($unidad)                                
                                @if ($data->unidad_id != $unidad)                                
                                    <div class="col-md-12">
                                        <table id="detalle" class="table table-bordered table-hover detallepago">
                                            <thead>
                                                <tr>
                                                    <th colspan="5" class="text-center">Solicitud Compra del Almacen</th>
                                                </tr>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>ENTIDAD + NRO COMPRA</th>
                                                    <th>PRECIO</th>
                                                    <th>STOCK</th>
                                                    <th width="5px">CANTIDAD</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success pull-right delete-confirm btn-save-customer" value="Sí, agregar">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    
    
        {{-- Para aprobar las solicitudes de pedidos --}}
        {!! Form::open(['route' => 'egres-solicitud.entregar', 'id'=>'entregarP', 'method' => 'post']) !!}

        <div class="modal modal-success fade" tabindex="-1" id="myModalEntregar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-bag-shopping"></i> Entregar Solicitud</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Aviso: </strong>
                            <p>Revise bien la cantidad de articulo a entregar..</p>
                        </div> 
                        <input type="hidden" name="id" id="id" value="{{$data->id}}">
    
                        <div style="display: block;" id="icons" class="text-center icons" style="text-transform:uppercase">
                            <i class="fa-solid fa-bag-shopping" style="color: rgb(134, 127, 127); font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea entregar la solicitud?</b></p>
                        </div>
                        <div style="display: none;" id="proce" class="text-center proce">
                            <img src="{{ asset('images/proce.gif') }}" alt="Voyager Loader">
                        </div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar entrega..!</label>
                    </div>                
                    <div class="modal-footer">
                        
                        <input type="submit" class="btn btn-success pull-right btn_submit" value="Sí, entregar">
                        {{-- <button type="submit" id="btn_submit" class="btn btn-success pull-right delete-confirm">Entregar Solicitud</button> --}}

                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close()!!} 

@stop


@section('css')
    <style>
        input:focus {
            background: rgb(197, 252, 215);
        }

        input:focus{        
            background: rgb(255, 245, 229);
            border-color: rgb(255, 161, 10);
            /* border-radius: 50px; */
        }
        input.text, select.text, textarea.text{ 
            border-radius: 5px 5px 5px 5px;
            color: #000000;
            border-color: rgb(63, 63, 63);
        }

    
        small{font-size: 32px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }
        #subtitle{
            font-size: 18px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }


        #detalle {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #detalle td, #detalle th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #detalle tr:nth-child(even){background-color: #f2f2f2;}

        #detalle tr:hover {background-color: #ddd;}

        #detalle th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .form-control .select2{
            border-radius: 5px 5px 5px 5px;
            color: #000000;
            border-color: rgb(63, 63, 63);
        }
        

    </style>
@stop

@section('javascript')
<script>

    $(document).ready(function(){
        $('#entregarP').submit(function(e){
            $('.btn_submit').val('Guardando...');
            $('.btn_submit').attr('disabled', true);
            $('.proce').css('display', 'block');
            $('.icons').css('display', 'none');
            $('.checkbox-inline').css('display', 'none');

            


        });
    })


    $('#show-modal').on('show.bs.modal', function (event)
    {
        // alert({{$unidad}})
        var unidad_id = {{$data->unidad_id}};
        // alert(unidad_id)
        var unidad = {{$unidad?$unidad:0}};
        // alert(unidad)
        var button = $(event.relatedTarget);
        var item = button.data('item');

        var detalle_id = button.data('detalle_id');
        var partida = button.data('partida');
        var article = button.data('article');
        var cantsolicitada = button.data('cantidad');

        var unidad_id = button.data('unidad_id');
        var article_id =button.data('article_id');

        var modal = $(this);

        // alert(user);

        modal.find('.modal-body #detalles_id').val(detalle_id);
        modal.find('.modal-body #label-partida').text(partida);
        modal.find('.modal-body #label-article').text(article);
        modal.find('.modal-body #label-cantidad').text('Cant. '+cantsolicitada);

        $('.detalle tbody').empty();
        $('.detallepago tbody').empty();

        $.get('{{route('egres-ajax.articleunidad')}}/'+unidad_id+'/'+article_id, function(data){
                    for (var i=0; i<data.length; i++) {

                        $('.detalle tbody').append(`
                            <tr>
                                <td><small>${i+1}</small></td>
                                <td><small>${data[i].nrosolicitud}</small></td>
                                <td class="text-right"><small>Bs. ${data[i].precio}</small></td>                              
                                <td class="text-right"><small>${data[i].cantidad}</small></td>                              
                                <td class="text-right">
                                    <input type="hidden" name="almacen[]" value="no">   
                                    <input type="hidden" name="detalle[]" value="${data[i].detalle_id}">   
                                    <input type="number" name="cantidad[]" min="0" max="${data[i].cantidad}" value="0" step=".1" onkeypress="return filterFloat(event,this);" style="text-align: right; width: 100px; height: 36px" class="text" required>                                 
                                </td> 

                                
                            </tr>
                        `);
                    }
                    if(data == '')
                    {
                        $('.detalle tbody').append(`
                            <tr style="text-align: center">
                                <td colspan="5">No se encontraron articulos.</td>
                            </tr>
                        `);
                    }
        });

        if(unidad != 0 && unidad != unidad_id)
        {
            $.get('{{route('egres-ajax.articlealmacen')}}/'+article_id, function(data){
                            //  alert(data)
                        for (var i=0; i<data.length; i++) {
                            $('.detallepago tbody').append(`
                                <tr>
                                    <td><small>${i+1}</small></td>
                                    <td><small>${data[i].nrosolicitud}</small></td>
                                    <td class="text-right"><small>Bs. ${data[i].precio}</small></td>                              
                                    <td class="text-right"><small>${data[i].cantidad}</small></td>                              
                                    <td class="text-right">
                                        <input type="hidden" name="almacen[]" value="si">   
                                        <input type="hidden" name="detalle[]" value="${data[i].detalle_id}">   
                                        <input type="number" name="cantidad[]" min="0" max="${data[i].cantidad}" value="0" step=".1" onkeypress="return filterFloat(event,this);" style="text-align: right; width: 100px; height: 36px" class="text" required>                                    
                                    </td>   
                                </tr>
                            `);
                        }
                        
                        // $('#label-total-payment').text('Bs. '+Number(pago).toString());
                        // $('#label-debt').text('Bs. '+(item.amount - pago));
                        if(data == '')
                        {
                            $('.detallepago tbody').append(`
                                <tr style="text-align: center">
                                    <td colspan="5">No se encontraron articuloss.</td>
                                </tr>
                            `);
                        }
            });
        }            
    })

    $(function()
    {
            $('#form-create-customer').submit(function(e){
                e.preventDefault();
                $('.btn-save-customer').attr('disabled', true);
                $('.btn-save-customer').val('Guardando...');
                $.post($(this).attr('action'), $(this).serialize(), function(data){
                    // alert(data)
                    if(data.detalle.id){
                        // alert(11)
                        toastr.success('Agregado', 'Éxito');
                        $(this).trigger('reset');
                        $('#cantentregar-'+data.detalle.id).text(data.detalle.cantentregada)
                    }else{
                        // alert(00)
                        toastr.error('', 'Error');
                        $('#cantentregar-'+data.detalle).text('0')
                    }
                })
                .always(function(){
                    // alert(00)

                    $('.btn-save-customer').attr('disabled', false);
                    $('.btn-save-customer').val('Guardar');
                    $('#show-modal').modal('hide');
                });
            });
    })


</script>
<script>
       function filterFloat(evt,input){
                // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
                var key = window.Event ? evt.which : evt.keyCode;    
                var chark = String.fromCharCode(key);
                var tempValue = input.value+chark;
                if(key >= 48 && key <= 57){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        return true;
                    }
                }
        }
       function filter(__val__){
                var preg = /^([0-9]+\.?[0-9]{0,1})$/; 
                if(preg.test(__val__) === true){
                    return true;
                }else{
                return false;
                }
                
        }
</script>

@stop
