{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
@extends('voyager::master')

@section('page_title', 'Editar Egreso')

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
@if(auth()->user()->hasPermission('edit_income'))
    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 id="subtitle" class="page-title">
                    <i class="voyager-basket"></i> Editar Egresos
                </h1>
                <a href="{{ route('income.index') }}" class="btn btn-warning btn-add-new">
                    <i class="fa-solid fa-arrow-rotate-left"></i> <span>Volver</span>
                </a>
            </div>
        </div>
    @stop

    @section('content')    
        <div id="app">
            <div class="page-content browse container-fluid" >
                @include('voyager::alerts')
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">                            
                                <div class="table-responsive">
                                     
                                       
                                        <div class="card-body">
                                            <h5 id="subtitle">Solicitud de Compras</h5>
                                            <div class="row">
                                                @php
                                                    $direccion = \DB::connection('mamore')->table('unidades')
                                                                    ->where('id', $solicitud->unidadadministrativa)
                                                                    ->select('*')
                                                                    ->orderBy('nombre','asc')
                                                                    ->first();
                                                    $unidades = \DB::connection('mamore')->table('unidades')
                                                                    ->where('direccion_id', $direccion->direccion_id)
                                                                    ->select('*')
                                                                    ->orderBy('nombre','asc')
                                                                    ->get();
                                                    // dd($unidades);
                                                @endphp
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="branchoffice_id" class="form-control select2" required>
                                                                <option value="" disabled selected>Seleccione una sucursal</option>
                                                                @foreach ($sucursales as $sucursal)
                                                                    <option value="{{$sucursal->id}}" {{ $sucursal->id == $solicitud->sucursal_id ? 'selected' : '' }}>{{$sucursal->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Sucursal (Usuario del Sistema).</small>
                                                    </div>
                                                </div>                                            
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="das" name="direccionadministrativa" class="form-control select2" required>
                                                                <option value="" disabled selected>Seleccione una Direccion Administrativa</option>
                                                                @foreach ($da as $data)
                                                                    <option value="{{$data->id}}" {{$data->id == $direccion->direccion_id? 'selected':''}}>{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Direción Administrativa.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="unidadEje" name="unidadadministrativa" class="form-control select2" required>
                                                                @foreach ($unidades as $item)
                                                                    <option value="{{$item->id}}" {{$item->id == $solicitud->unidadadministrativa? 'selected':''}}>{{$item->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Unidad Administrativa.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="modalidad" class="form-control select2" >
                                                                
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Modalidad de Compra.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" name="nropedido" class="form-control form-control-sm text" placeholder="Numero de Pedido" value="{{$solicitud->nropedido}}" required>
                                                        </div>
                                                        <small>Numero de Pedido.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" name="fechasolicitud" class="form-control text" value="{{$solicitud->fechasolicitud}}" required>
                                                        </div>
                                                        <small>Fecha Solicitud.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" name="fechaegreso" class="form-control text" value="{{$solicitud->fechaegreso}}" required>
                                                        </div>
                                                        <small>Fecha Egreso.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        <h5 id="subtitle">Articulo:</h5>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <select id="article_id" class="form-control select2">
                                                        
                                                    </select>
                                                    <small>Articulo.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" id="presentacion" class="form-control form-control-sm text" placeholder="Seleccione un Articulo" readonly>
                                                    </div>
                                                    <small>Presentación.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" id="precio" class="form-control form-control-sm text" placeholder="Seleccione un Articulo" readonly>
                                                    </div>
                                                    <small>Precio.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" id="stok" class="form-control form-control-sm text" placeholder="Seleccione un Articulo" readonly>
                                                    </div>
                                                    <small>Stock Artículo.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" id="cantidad" step="0.01" class="form-control form-control-sm text" placeholder="Introducir monto" autocomplete="off">
                                                    </div>
                                                    <small>Cantidad.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <button type="button" id="bt_add" class="btn btn-success"><i class="voyager-basket"></i> Agregar Artículo</button>
                                            </div>
                                        </div>
                                    
                                        <table id="detalles" class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Id</th>
                                                    <th>Modalidad + Nro Solicitud Compra</th>                                                    
                                                    <th>Articulo</th>
                                                    <th>Presentación</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Art.</th>
                                                    <th>SubTotal</th>
                
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total = 0;
                                                @endphp
                                                @foreach($detail as $item)                                                   
                                                    <tr class="selected" id="fila{{$item->detallefactura_id}}">
                                                        <td>                                                        
                                                            <button 
                                                                type="button" 
                                                                title="Eliminar articulo"
                                                                class="btn btn-danger" 
                                                                onclick="eliminar('{{$item->detallefactura_id}}')";
                                                            >
                                                            <i class="voyager-trash"></i>
                                                            </button>                                                            
                                                        </td>
                                                        <td>{{$item->detallefactura_id}}</td>
                                                        <td>{{$item->modalidad}} - {{$item->nrosolicitud}}</td>
                                                        <td>{{$item->article}}</td>
                                                        <td>{{$item->presentacion}}</td>
                                                        <td>{{$item->cantsolicitada}}</td>
                                                        <td>{{$item->precio}}</td>
                                                        <td>{{$item->totalbs}}</td>
                                                        {{-- <td><input type="hidden" class="input_article" name="article_id[]" value="{{ $item->articulo_id}}">{{$item->articulo}}</td>
                                                        <td>{{ $item->presentacion}}</td>
                                                        <td><input type="hidden" name="cantidad[]" value="{{ $item->cantsolicitada }}">{{ $item->cantsolicitada }}</td>
                                                        <td><input type="hidden" name="precio[]" value="{{ $item->precio }}">{{ $item->precio }}</td>
                                                        <td><input type="hidden" class="input_subtotal" name="totalbs[]" value="{{ $item->totalbs }}">{{ $item->totalbs }}</td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <th colspan="7" style="text-align:right"><h5>TOTAL</h5></th>
                                                <th><h4 id="total">Bs. 0.00</h4></th>
                                            </tfoot>
                                            
                                        </table>
                                        <div class="card-footer">
                                            <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                        </div>  
                                                      
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>          
    @stop


    @section('css')
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stop

    @section('javascript')
    <script>
            $(document).ready(function() {
                $(".select2").select2({theme: "classic"});

                $('.select2bs4').select2({});

                $('#das').on('change', unidad_administrativa);

                $('#unidadEje').on('change', onselect_modalidad_compra);

                $('#modalidad').on('change', onselect_article);

                $('#article_id').on('change', onselect_presentacion);



                
                // var cod ='{{$solicitud->unidadadministrativa}}';
                // alert(cod)

                // modalidad_comp(cod);



                $('#provider').on('change', onselect_proveedor_llenar);
                $('#tipofactura').on('change', onselect_tipo);

        
                

                $('#bt_add').click(function() {
                    agregar();
                });
            });


            //variables.
            var cont=0;
            total=0;
            subtotal=[];


            var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
            
                partida=$("#partida option:selected").text();
                montofactura=$("#montofactura").val();
                article_id=$("#article_id").val();
                presentacion=$("#presentacion").val();
                cantidad=$("#cantidad").val();
                precio=$("#precio").val();
                nombre_articulo=$("#article_id option:selected").text();

                var arrayarticle = [];
                var i=0;
                var j=0;
                ok=false;
                if (partida != 'Seleccione una Partida..' && nombre_articulo != 'Seleccione un Articulo..' && cantidad != "" && precio != "") {
                    


                    var fila='<tr class="selected" id="fila'+article_id+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+article_id+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td>'+partida+'</td>'
                            fila+='<td><input type="hidden" class="input_article" name="article_id[]"value="'+article_id+'">'+nombre_articulo+'</td>'
                            fila+='<td>'+presentacion+'</td>'
                            fila+='<td><input type="hidden"  class="form-control" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'
                            fila+='<td><input type="hidden"  class="form-control" name="precio[]" value="'+precio+'">'+precio+'</td>'
                            fila+='<td><input type="hidden" class="input_subtotal" name="totalbs[]" value="'+cantidad * precio+'">'+cantidad * precio+'</td>'
                        fila+='</tr>';
                        

                    let detalle_subtotal = parseFloat(calcular_total()+cantidad * precio ).toFixed(2);
                    let monto_factura = parseFloat($('#montofactura').val());
                        

                    if (detalle_subtotal <= monto_factura) {

                        $(".input_article").each(function(){
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;
                        }); 
                        var ok=true;
                        // alert(arrayarticle.length)
                        for(j=0;j<arrayarticle.length; j++)
                        {
                            
                            if(arrayarticle[j] == article_id)
                            {
                                // cont--;
                                limpiar();
                                ok = false;
                                // eliminar(arrayarticle.length-1)
                                swal({
                                    title: "Error",
                                    text: "El Articulo ya Existe en la Lista",
                                    type: "error",
                                    showCancelButton: false,
                                    });
                                div = document.getElementById('flotante');
                                div.style.display = '';
                                return;                                
                            }
                        }
                        if(ok==true)
                        {
                            // cont++;
                        
                            limpiar();
                            $('#detalles').append(fila);
                            $("#total").html("Bs. "+calcular_total().toFixed(2));
                            // $("#totals").html(calcular_total().toFixed(2));
                            $("#totals").val(calcular_total().toFixed(2));
                            if (calcular_total().toFixed(2)==monto_factura.toFixed(2)) {
                                $('#btn_guardar').removeAttr('disabled');
                            }
                            else
                            {
                                $('#btn_guardar').attr('disabled', true);
                            }
                        }
                    }
                    else
                    {
                        swal({
                            title: "Error",
                            text: "El monto total supera al monto de la factura",
                            type: "error",
                            showCancelButton: false,
                            });
                        div = document.getElementById('flotante');
                        div.style.display = '';
                        return;
                    }
                }
                else
                {
                    swal({
                            title: "Error",
                            text: "Rellene los Campos de las Seccion de Articulo",
                            type: "error",
                            showCancelButton: false,
                            });
                        div = document.getElementById('flotante');
                        div.style.display = '';
                        return;
                }

            }      


            function check(e,index) 
            {   
                alert(index)
                    tecla = (document.all) ? e.keyCode : e.which;

                    //Tecla de retroceso para borrar, siempre la permite
                    if (tecla == 8) {
                
                        return true;
                    }

                    var numero =0;
                    var letra =0;
                    // Patron de entrada, en este caso solo acepta numeros y letras
                    patron = /[0-9]/;
                    tecla_final = String.fromCharCode(tecla);
                    alert(tecla_final)
                                
                        
                                    
            }
            
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
            }


            function eliminar(index)
            {
                // total=total-subtotal[index];
                // alert(subtotal[index])
                // $("#total").html("Bs/." + total);
                $("#fila" + index).remove();
                $("#total").html("Bs. "+calcular_total().toFixed(2));
                $("#totals").val(calcular_total().toFixed(2));
                // evaluar();
                $('#btn_guardar').attr('disabled', true);
            }

            //calcular total de factura
            function calcular_total()
            {
                let total = 0;
                $(".input_subtotal").each(function(){
                    total += parseFloat($(this).val());
                    // alert(parseFloat($(this).val()));
                });
                // console.log(total);
                
                return total;
            }
            function sumfila()
            {

            }

            function subTotal(index){

                
                let cant = $(`#montofactura`).val() ? parseFloat($(`#montofactura`).val()) : 0;
                let totals = $(`#totals`).val() ? parseFloat($(`#totals`).val()) : 0;

                if(cant == totals)
                {
                    $('#btn_guardar').removeAttr('disabled');
                }
                else
                {
                    $('#btn_guardar').attr('disabled', true);
                }

            }





            function unidad_administrativa() //funciona
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_unidad_administrativa')}}/'+id, function(data){
                        var html_unidad=    '<option value="">Seleccione Una Unidad Administrativa..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_unidad += '<option value="'+data[i].id+'">'+data[i].id+'-'+data[i].nombre+'</option>'

                        $('#unidadEje').html(html_unidad);;            
                    });
                }
                else
                {
                    var html_unidad=    ''       
                    $('#unidadEje').html(html_unidad);
                }
            }

            function onselect_modalidad_compra()
            {
                // alert(4)
                $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');
                    var html_articulo=    ''       
                    $('#article_id').html(html_articulo);

                var id =  $(this).val(); 
                // alert(id)   
                if(id >=1)
                {
                    $.get('{{route('ajax_solicitud_compra')}}/'+id, function(data){
                        var html_modalidad=    '<option value="">Seleccionar Modalidad Compra..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_modalidad += '<option value="'+data[i].id+'">'+data[i].nombre+' - '+data[i].nrosolicitud+'</option>'

                        $('#modalidad').html(html_modalidad);;            
                    });
                    $(".selected").remove();
                    $("#total").html("Bs. 0.00");

                }
                else
                {
                    var html_modalidad=    ''       
                    $('#modalidad').html(html_modalidad);
                    
    
                }
            }


            function onselect_article()
            {
                var id =  $(this).val();    
                $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');
                if(id >=1)
                {
                    $.get('{{route('ajax_egres_select_article')}}/'+id, function(data){
                        var html_articulo=    '<option value="">Seleccione un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_articulo += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#article_id').html(html_articulo);;            
                    });
                }
                else
                {
                    var html_articulo=    ''       
                    $('#article_id').html(html_articulo);
                    
                }
            }




            function onselect_presentacion()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_egres_select_article_detalle')}}/'+id, function(data){
                        $("#precio").val(data[0].precio);
                        $("#stok").val(data[0].cantrestante);
                        $("#presentacion").val(data[0].presentacion);
                                    
                    });
                }
                else
                {
                    $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');
    
                }            
            }



            function modalidad_comp(id)
            {
                // alert(4)
                $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');
                    var html_articulo=    ''       
                    $('#article_id').html(html_articulo);

                var id = id;
                // alert(id)   
                if(id >=1)
                {
                    $.get('{{route('ajax_solicitud_compra')}}/'+id, function(data){
                        var html_modalidad=    '<option value="">Seleccionar Modalidad Compra..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_modalidad += '<option value="'+data[i].id+'">'+data[i].nombre+' - '+data[i].nrosolicitud+'</option>'

                        $('#modalidad').html(html_modalidad);;            
                    });
                    $(".selected").remove();
                    $("#total").html("Bs. 0.00");

                }
                else
                {
                    var html_modalidad=    ''       
                    $('#modalidad').html(html_modalidad);
                    
    
                }
            }



        
        </script>
    @stop
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif