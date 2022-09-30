{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')

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
                    <i class="voyager-basket"></i> Editar Ingreso
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
                                    <main class="main">        
                                        {!! Form::open(['route' => 'income_update', 'id'=>'1', 'class' => 'was-validated'])!!}
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
                                                                <option value="">Seleccione una sucursal</option>
                                                                @foreach ($sucursal as $item)
                                                                    <option value="{{$item->sucursal->id}}" {{ $item->sucursal->id == $solicitud->sucursal_id ? 'selected' : '' }}>{{$item->sucursal->nombre}}</option>
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
                                                                <option value="">Seleccione una Direccion Administrativa</option>
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
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" name="fechaingreso" class="form-control text" value="{{$sol->fechaingreso}}" required>
                                                        </div>
                                                        <small>Fecha Ingreso.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="modality_id" class="form-control select2" required>
                                                                <option value="">Seleccione una Modalidad de Compra</option>
                                                                @foreach ($modalidad as $data)
                                                                    <option value="{{$data->id}}" {{ $data->id == $sol->modality_id ? 'selected' : '' }}>{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Modalidad de Compra.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <!-- <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" disabled class="form-control form-control-sm" name="nrosolicitud" value="{{$sol->nrosolicitud}}" placeholder="Introducir número de solicitud" autocomplete="off" required>
                                                        </div>
                                                        <small>Número Solicitud.</small>
                                                    </div>
                                                </div> -->
                                                <!-- === -->
                                                
                                                <!-- === -->
                                            </div>
                                            <hr>
                                            <h5 id="subtitle">Proveedor + Detalle de Factura:</h5>
                                                {{-- <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="file" name="file" id="archivo" class="form-control form-control-sm" placeholder="Seleccione un Proveedor" accept="application/pdf">
                                                    </div>
                                                    <small>Archivos.</small>
                                                </div> --}}
    
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="provider" name="provider_id"  class="form-control select2" required>
                                                                <option value="">Seleccione un Proveedor</option>
                                                                @foreach ($proveedor as $data)
                                                                    <option value="{{$data->id}}_{{$data->nit}}_{{$data->responsable}}" {{ $data->id == $factura->provider_id ? 'selected' : '' }}>{{$data->razonsocial}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Proveedor.</small>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="provider_id" name="provider_id" value="{{$proveedorselect->id}}">
                                            
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="nit" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" value="{{$proveedorselect->nit}}" disabled readonly>
                                                        </div>
                                                        <small>NIT.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="responsable" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" value="{{$proveedorselect->responsable}}" disabled >
                                                        </div>
                                                        <small>Responsable.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="tipofactura" id="tipofactura"class="form-control text" required>
                                                                <option value="Manual" {{ 'Manual' == $factura->tipofactura ? 'selected' : '' }}>Manual</option>
                                                                <option value="Electronica" {{ 'Electronica' == $factura->tipofactura ? 'selected' : '' }}>Electronica</option>
                                                                <option value="Orden_Compra" {{ 'Orden_Compra' == $factura->tipofactura ? 'selected' : '' }}>Orden de Compra</option>
                                                            </select>
                                                        </div>
                                                        <small>Tipo Factura.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" id="fechafactura" name="fechafactura" value="{{$factura->fechafactura}}" class="form-control text">
                                                        </div>
                                                        <small>Fecha Factura.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- === -->
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" step="0.01" onchange="subTotal()" onkeyup="subTotal()" class="form-control form-control-sm text" id="montofactura" value="{{$factura->montofactura}}" name="montofactura" placeholder="Introducir monto" autocomplete="off" required>
                                                        </div>
                                                        <small>Monto *(Bs).</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="nrofactura" name="nrofactura" class="form-control text" value="{{$factura->nrofactura}}" title="Introducir Nro de Factura" required>
                                                        </div>
                                                        <small>Numero.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    @if ($factura->tipofactura == 'Manual' || $factura->tipofactura == 'Electronica')
                                                        <div class="form-group" id="nroautorizacion">
                                                            <div class="form-line">
                                                                <input type="number"  name="nroautorizacion" id="nroautorizacion" class="form-control text" value="{{$factura->nroautorizacion}}" title="Introducir Nro Autorizacion de la Factura" required>
                                                            </div>
                                                            <small>Nro Autorizacion Fact.</small>
                                                        </div>
                                                    @endif                                                    
                                                </div>
                                                <div class="col-sm-2" id="nrocontrol">
                                                    @if ($factura->tipofactura == 'Electronica')
                                                        <div class="form-group" id="nrocontrol">
                                                            <div class="form-line">
                                                                <input type="number"  name="nrocontrol" id="nrocontrol" class="form-control text" value="{{$factura->nrocontrol}}" title="Introducir Nro de Control" required>
                                                            </div>
                                                            <small>Nro Autorizacion Fact.</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr>
                                            <h5 id="subtitle">Partida + Articulo:</h5>
                                            <div class="row">
                                            <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="partida"class="form-control select2">
                                                                <option value="">Seleccione una Partida..</option>
                                                                @foreach($partida as $data)
                                                                    <option value="{{$data->id}}">{{$data->codigo}} - {{$data->nombre}}</option>                                                                
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Partidas.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select id="article_id" class="form-control select2">
                                                            
                                                        </select>
                                                        <small>Articulo.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- === -->
                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text"disabled class="form-control form-control-sm text" id="presentacion" autocomplete="off">
                                                        </div>
                                                        <small>Presentacion.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="cantidad" class="form-control text" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad Artículo.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="precio" class="form-control text" title="Precio Unitario Bs">
                                                        </div>
                                                        <small>Precio Artículo *(Bs).</small>
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
                                                        <th>Partida</th>
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
                                                    @foreach($detalle as $item)
                                                        @php
                                                            // $cont++;
                                                            $total += $item->totalbs;
                                                        @endphp
                                                        <tr class="selected" id="fila{{$item->articulo_id}}">
                                                            <td>
                                                            
                                                                <button 
                                                                    type="button" 
                                                                    title="Eliminar articulo"
                                                                    class="btn btn-danger" 
                                                                    onclick="eliminar('{{$item->articulo_id}}')";
                                                                >
                                                                <i class="voyager-trash"></i>
                                                                </button>
                                                                
                                                            </td>
                                                            <td>{{$item->codigo}} - {{$item->partida}}</td>
                                                            <td><input type="hidden" class="input_article" name="article_id[]" value="{{ $item->articulo_id}}">{{$item->articulo}}</td>
                                                            <td>{{ $item->presentacion}}</td>
                                                            <td><input type="hidden" name="cantidad[]" value="{{ $item->cantsolicitada }}">{{ $item->cantsolicitada }}</td>
                                                            <td><input type="hidden" name="precio[]" value="{{ $item->precio }}">{{ $item->precio }}</td>
                                                            <td><input type="hidden" class="input_subtotal" name="totalbs[]" value="{{ $item->totalbs }}">{{ $item->totalbs }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="6" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4  id="total">Bs. {{ $total }}</h4></th>
                                                    <input type="hidden" step="0.01" name="total" value="{{ $total }}" id="totals">
                                                </tfoot>
                                                
                                            </table>
                                            
                                        </div>   
                                        <input type="hidden" name="id" value="{{$sol->id}}">
                                        <div class="card-footer">
                                            <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                        </div>   
                                        {!! Form::close() !!}                     
                                    </main>
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



                $('#provider').on('change', onselect_proveedor_llenar);
                $('#tipofactura').on('change', onselect_tipo);

                $('#partida').on('change', onselect_article);
                $('#article_id').on('change', onselect_presentacion);

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

            function unidad_administrativa()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_unidad_administrativa')}}/'+id, function(data){
                        var html_unidad=    '<option value="">Seleccione Una Unidad Administrativa..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_unidad += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#unidadEje').html(html_unidad);;            
                    });
                }
                else
                {
                    var html_unidad=    ''       
                    $('#unidadEje').html(html_unidad);
                }
            }







            






            function onselect_tipo()
            {
                
                var tipo =  $(this).val();   
                var html_factura = '';
                if(tipo == 'Electronica')
                {
                    html_nroautorizacion =  '<div class="form-group">'
                    html_nroautorizacion+=       '<div class="form-line">'
                    html_nroautorizacion+=            '<input type="number" name="nroautorizacion" placeholder="Introducir Nro Autorizacion" class="form-control text" title="Introducir Nro de Autorización">'
                    html_nroautorizacion+=       '</div>'
                    html_nroautorizacion+=       '<small>Nro Autorizacion.</small>'
                    html_nroautorizacion+=  '</div>'
                    $('#nroautorizacion').html(html_nroautorizacion);

                    html_factura =  '<div class="form-group">'
                    html_factura+=       '<div class="form-line">'
                    html_factura+=            '<input type="number" name="nrocontrol" class="form-control text" placeholder="Introducir Nro Control" title="Introducir Nro Control Factura">'
                    html_factura+=       '</div>'
                    html_factura+=       '<small>Nro Control.</small>'
                    html_factura+=  '</div>'
                    $('#nrocontrol').html(html_factura);
                }
                else
                {
                    if(tipo == 'Orden_Compra')
                    {      
                        html_nroautorizacion=  '';
                        $('#nroautorizacion').html(html_nroautorizacion);                  
                        html_factura ='';
                        $('#nrocontrol').html(html_factura);
                    }
                    else
                    {

                        html_nroautorizacion =  '<div class="form-group">'
                        html_nroautorizacion+=       '<div class="form-line">'
                        html_nroautorizacion+=            '<input type="number" name="nroautorizacion" class="form-control text" placeholder="Introducir Nro Autorizacion" title="Introducir Nro de Autorización">'
                        html_nroautorizacion+=       '</div>'
                        html_nroautorizacion+=       '<small>Nro Control.</small>'
                        html_nroautorizacion+=  '</div>'
                        $('#nroautorizacion').html(html_nroautorizacion);

                        html_factura ='';
                        $('#nrocontrol').html(html_factura);
                    }
                    // html_factura ='';
                    // $('#nrocontrol').html(html_factura);
                }
            }

            function onselect_proveedor_llenar()
            {
                datoProveedor = document.getElementById('provider').value.split('_');
                // alert(datoProveedor[2]);
                $("#provider_id").val(datoProveedor[0]);
                $("#nit").val(datoProveedor[1]);
                $("#responsable").val(datoProveedor[2]);
            }

            function onselect_article()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_article')}}/'+id, function(data){
                        var html_article=    '<option value="">Seleccione un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_article += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#article_id').html(html_article);           
                    });
                }
                else
                {
                    var html_article=    ''       
                    $('#article_id').html(html_article);
                    $("#presentacion").val('');
                }
            }
            function onselect_presentacion()
            {
                // datoProveedor = document.getElementById('provider').value.split('_');
                // alert(datoProveedor[2]);
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_presentacion')}}/'+id, function(data){
                                // alert(data.presentacion)
                        $("#presentacion").val(data.presentacion);
                    });
                }
                else
                {
                    $("#presentacion").val('');
                    
                }
                // $("#provider_id").val(datoProveedor[0]);
                // $("#nit").val(datoProveedor[1]);
                // $("#responsable").val(datoProveedor[2]);
            }

        </script>
    @stop
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif