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
                <a data-toggle="modal" data-target="#myModalEntregar" title="Aprobar Solicitud" class="btn btn-sm btn-success view">
                    <i class="fa-solid fa-bag-shopping"></i> Entregar Pedido
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
                                                        <input type="number" name="cantidad[]" id="amount" min="0" step=".1" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" style="text-align: right; width: 100px; height: 36px" class="text" required>                                    
                                                        <a href="#" data-toggle="modal" data-target="#show-modal" 
                                                            data-article_id="{{$item->article_id}}"
                                                            data-article="{{strtoupper($item->article->nombre)}}"
                                                            data-partida="{{strtoupper($item->article->partida->codigo.'-'.$item->article->partida->nombre)}}"
                                                            data-cantidad="{{$item->cantsolicitada}}"
                                                            data-unidad_id="{{$data->unidad_id}}"
                                                             data-item='@json($item)' title="Ver" class="btn btn-sm btn-warning view">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       

    {{-- Modal para ver los detalle del stock del producto de que unidad tiene --}}
    <div class="modal fade" tabindex="-1" id="show-modal" role="dialog">
        <div class="modal-dialog modal-warning modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-basket"></i> Detalles de la Venta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Partida</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-partida">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Articulo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-article">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cantidad Solicitada</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-cantidad">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        <div class="col-md-12">
                            <table id="detalle" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center">Solicitud Compra de la Unidad</th>
                                    </tr>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>ENTIDAD + NRO COMPRA</th>
                                        <th>PRECIO</th>
                                        <th>CANTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table id="detallepago" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center">Solicitud Compra del Almacen</th>
                                    </tr>
                                    <tr>
                                        <th>N&deg;</th>
                                        {{-- <th>Registrado por</th> --}}
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>PAGO TOTAL</b></td>
                                        <td class="text-right"><b style="font-size: 18px" id="label-total-payment">0,00 Bs.</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>DEUDA TOTAL</b></td>
                                        <td class="text-right"><b style="font-size: 18px" id="label-debt">0,00 Bs.</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    
    
        {{-- Para aprobar las solicitudes de pedidos --}}
        <div class="modal modal-success fade" tabindex="-1" id="myModalEntregar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'inbox.aprobar', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-bag-shopping"></i> Entregar Solicitud</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="id" id="id" value="{{$data->id}}">
    
                        <div class="text-center" style="text-transform:uppercase">
                            <i class="fa-solid fa-bag-shopping" style="color: rgb(134, 127, 127); font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea entregar la solicitud?</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, entregar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
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


        #detalles {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #detalles td, #detalles th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #detalles tr:nth-child(even){background-color: #f2f2f2;}

        #detalles tr:hover {background-color: #ddd;}

        #detalles th {
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
    $('#show-modal').on('show.bs.modal', function (event)
    {
        var button = $(event.relatedTarget);
        var item = button.data('item');

        var partida = button.data('partida');
        var article = button.data('article');
        var cantsolicitada = button.data('cantidad');

        var unidad_id = button.data('unidad_id');
        var article_id =button.data('article_id');

        var modal = $(this);

        // alert(user);

        modal.find('.modal-body #label-partida').text(partida);
        modal.find('.modal-body #label-article').text(article);
        modal.find('.modal-body #label-cantidad').text('Cant. '+cantsolicitada);


        $.get('{{route('egres-ajax.articleunidad')}}/'+unidad_id+'/'+article_id, function(data){
            // alert(data);
                    // var pago= 0;                    
                    for (var i=0; i<data.length; i++) {
                        // pago = pago+ data[i].cant;
                        // pago = pago + parseInt(data[i].cant?data[i].cant:0);
                        $('#detalle tbody').append(`
                            <tr>
                                <td style="width: 50px">${i+1}</td>
                                <td style="width: 50px">${data[i].nrosolicitud}</td>
                                <td style="width: 50px">${data[i].precio}</td>
                                <td style="width: 50px" class="text-right">${data[i].cantidad}</td>                              
                                
                            </tr>
                        `);
                    }
                    
                    $('#label-total-payment').text('Bs. '+Number(pago).toString());
                    $('#label-debt').text('Bs. '+(item.amount - pago));
        });

        $.get('{{route('egres-ajax.articlealmacen')}}/'+article_id, function(data){
            // alert(data);
                    // var pago= 0;                    
                    for (var i=0; i<data.length; i++) {
                        // pago = pago+ data[i].cant;
                        // pago = pago + parseInt(data[i].cant?data[i].cant:0);
                        $('#detallepago tbody').append(`
                            <tr>
                                <td style="width: 50px">${i+1}</td>
                                <td style="width: 50px">${data[i].nrosolicitud}</td>
                                <td style="width: 50px">${data[i].precio}</td>
                                <td style="width: 50px" class="text-right">${data[i].cantidad}</td>                              
                                
                            </tr>
                        `);
                    }
                    
                    $('#label-total-payment').text('Bs. '+Number(pago).toString());
                    $('#label-debt').text('Bs. '+(item.amount - pago));
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
                // else{
                //     if(key == 8 || key == 13 || key == 46 || key == 0) {            
                //         return true;              
                //     }else{
                //         return false;
                //     }
                // }
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
