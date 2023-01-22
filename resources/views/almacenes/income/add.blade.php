@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')

<style>
    input:focus {
  background: rgb(197, 252, 215);
}
</style>
<style>
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


   

    .form-control .select2{
        border-radius: 5px 5px 5px 5px;
        color: #000000;
        border-color: rgb(63, 63, 63);
    }
    

</style>

@if(auth()->user()->hasPermission('add_income'))

    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 id="subtitle" class="page-title">
                    <i class="voyager-basket"></i> Añadir Ingreso
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
                                        @if (!$gestion)
                                            <div class="alert alert-danger">
                                                <strong>Aviso: </strong>
                                                <p> No tiene una gestion activa, contactese con los administradores del sistema.</p>
                                            </div> 
                                        @endif

                                        
                                        {!! Form::open(['route' => 'income.store', 'class' => 'was-validated', 'id'=>'form-agregar'])!!}
                                        <div class="card-body">
                                            <input type="hidden" name="inventarioAlmacen_id" @if($gestion) value="{{$gestion->id}}" @endif>
                                            <input type="hidden" name="gestion" @if($gestion) value="{{$gestion->gestion}}" @endif>

                                            <h5 id="subtitle">Solicitud de Compras</h5>
                                            <div class="row">
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="branchoffice_id" class="form-control select2" required>
                                                                {{-- @foreach ($sucursal as $item) --}}
                                                                    <option value="{{$sucursal->id}}" selected>{{$sucursal->nombre}}</option>
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                                        <small>Sucursal.</small>
                                                    </div>
                                                </div>                                            
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="das" name="direccionadministrativa" class="form-control select2" required>
                                                                <option value="" selected disabled>Seleccione una Direccion Administrativa</option>
                                                                @foreach ($da as $data)
                                                                    <option value="{{$data->id}}">{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Direción Administrativa.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="unidadEje" name="unidadadministrativa" class="form-control select2" required>
                                                                
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Unidad Administrativa.</small>
                                                    </div>
                                                </div>
                                                

                                                <!-- === -->
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" name="fechaingreso" id="fechaingreso" class="form-control text" onchange="gestionVerification()" onkeyup="gestionVerification()" @if($gestion) min="{{$gestion->gestion.'-01-01'}}" max="{{$gestion->gestion.'-12-31'}}" @endif required>
                                                            <b class="text-danger" id="label-date" style="display:none">La gestion Correspondiente es incorrecta..</b>

                                                        </div>
                                                        <small>Fecha Ingreso.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="modality_id" class="form-control select2" required>
                                                                <option value="">Seleccione una Modalidad de Compra</option>
                                                                @foreach ($modalidad as $data)
                                                                    <option value="{{$data->id}}">{{$data->nombre}}</option>
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
                                                            <input type="text" class="form-control form-control-sm" name="nrosolicitud" placeholder="Introducir número de solicitud" autocomplete="off" required>
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
                                                            <select id="provider" name="provider_id" class="form-control select2" required>
                                                                <option value="">Seleccione un Proveedor</option>
                                                                @foreach ($proveedor as $data)
                                                                    <option value="{{$data->id}}_{{$data->nit}}_{{$data->responsable}}">{{$data->razonsocial}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Proveedor.</small>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="provider_id" name="provider_id">
                                            
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="nit" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" disabled readonly>
                                                        </div>
                                                        <small>NIT.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="responsable" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" disabled >
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
                                                                <option value="Manual">Factura Manual</option>
                                                                <option value="Electronica">Factura Electronica</option>
                                                                <option value="Orden_Compra">Orden de Compra</option>
                                                            </select>
                                                        </div>
                                                        <small>Tipo.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" id="fechafactura" name="fechafactura" class="form-control text">
                                                        </div>
                                                        <small>Fecha.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- === -->
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" step="0.01"  style="text-align: right" class="form-control form-control-sm text" id="montofactura" name="montofactura" placeholder="Introducir monto" value="0.00" disabled required>
                                                            <input type="hidden" step="0.01" name="total" value="0" id="totals" required>
                                                        </div>
                                                        <small>Monto *(Bs).</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="nrofactura" name="nrofactura" placeholder="Introducir Numero" class="form-control text" title="Introducir Nro de Factura" required>
                                                        </div>
                                                        <small>Numero.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2" id="nroautorizacion">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text"  name="nroautorizacion" id="nroautorizacion" placeholder="Introducir Nro Autorizacion" class="form-control text" title="Introducir Nro Autorizacion de la Factura" required>
                                                        </div>
                                                        <small>Nro Autorizacion Fact.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2" id="nrocontrol">
                                                    {{-- add tipo de factura --}}
                                                </div>
                                            </div>
                                            <hr>
                                            <h5 id="subtitle">Partida + Articulo:</h5>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="partida"class="form-control select2">
                                                                <option value="" selected disabled>--Seleccione una Partida--</option>
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
                                                            <input type="number" min="1" style="text-align: right" onkeypress="return filterFloat(event,this);" id="cantidad" placeholder="Cantidad Articulo" class="form-control text" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" style="text-align: right" onkeypress="return filterFloat(event,this);" id="precio" placeholder="Precio Unitario" class="form-control text" title="Precio Unitario Bs">
                                                        </div>
                                                        <small>Precio Unit. (Bs).</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <button type="button" id="bt_add" class="btn btn-success"><i class="voyager-basket"></i> Agregar Artículo</button>
                                                </div>
                                            </div>
                                        
                                            <table id="dataTableStyle" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center">Opción</th>
                                                        <th style="text-align: center">Partida</th>
                                                        <th style="text-align: center">Articulo</th>
                                                        <th style="text-align: center">Presentación</th>
                                                        <th style="text-align: center">Cantidad</th>
                                                        <th style="text-align: center">Precio Unit.</th>
                                                        <th style="text-align: center">SubTotal</th>
                    
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <th colspan="6" style="text-align: left"><h5>TOTAL</h5></th>
                                                    <th style="text-align: right"><h4 id="total">Bs. 0.00</h4></th>                                                    
                                                </tfoot>
                                                
                                            </table>
                                            
                                        </div>   
                                        @if ($gestion)
                                            <div class="card-footer">
                                                <button id="btn_guardar" disabled type="submit" style="display:none" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                            </div>   
                                        @endif
                                        {!! Form::close() !!}                   
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        {{-- <input type="text" onkeypress="return NumCheck(event, this)"/>      --}}
        {{-- <input type="text" name="moneda nac" id="moneda_nac" value="10" onkeypress="return filterFloat(event,this);"/>    --}}
    @stop


    @section('css')
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stop

    @section('javascript')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
        <script>
            $(document).ready(function(){
                $('#form-agregar').submit(function(e){
                    // $('#btn_guardar').css('display', 'none');
                    $('#btn_guardar').attr('disabled', true);

                });
            })

            $(function()
            {    
                $(".select2").select2({theme: "classic"});

                $('#das').on('change', unidad_administrativa);

                $('#provider').on('change', onselect_proveedor_llenar);

                $('#tipofactura').on('change', onselect_tipo);

                $('#partida').on('change', onselect_article);
                $('#article_id').on('change', onselect_presentacion);

                $('#bt_add').click(function() {
                    agregar();
                });

                gestionVerification();

            })

            //para establecer la gestion  solo para navegador FIREFOX
            function gestionVerification()
            {
                let fecha = $(`#fechaingreso`).val() ? parseFloat($(`#fechaingreso`).val()) : 0;
                
                let gestion = {{$gestion ? $gestion->gestion : 0}};
    
                if(fecha == gestion)
                {
                    $('#btn_guardar').css('display', 'block');
                    $('#label-date').css('display', 'none');
                }
                else
                {
                    $('#btn_guardar').css('display', 'none');

                    $('#label-date').css('display', 'block');
                }
            }



            var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
                partida=$("#partida option:selected").text();
                // montofactura=$("#montofactura").val();
                article_id=$("#article_id").val();
                presentacion=$("#presentacion").val();

                cantidad=parseFloat($("#cantidad").val()?$("#cantidad").val():0).toFixed(2);
                precio=parseFloat($("#precio").val()? $("#precio").val():0).toFixed(2);

                subT = cantidad * precio;

                nombre_articulo=$("#article_id option:selected").text();

                var arrayarticle = [];
                var i=0;
                var j=0;
                ok=false;

                // alert(cantidad)

                if (partida != 'Seleccione una Partida..' && nombre_articulo != 'Seleccione un Articulo..' && cantidad > 0 && precio > 0) {
                    
                  

                    var fila='<tr class="selected" id="fila'+article_id+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+article_id+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td>'+partida+'</td>'
                            fila+='<td><input type="hidden" class="input_article" name="article_id[]"value="'+article_id+'">'+nombre_articulo+'</td>'
                            fila+='<td>'+presentacion+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" class="input_subtotal" name="subtotal[]" value="'+(subT).toFixed(2)+'">'+(subT).toFixed(2)+'</td>'
                        fila+='</tr>';
                    
                        $(".input_article").each(function(){
                            // alert(parseFloat($(this).val()))
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;
                        }); 
                        var ok=true;
                        // alert(arrayarticle.length)
                        for(j=0;j<arrayarticle.length; j++)
                        {
                            
                            if(arrayarticle[j] == article_id)
                            {
                                limpiar();
                                ok = false;

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'El artículo ya existe en la lista..',
                                })                             
                            }
                        }
                        if(ok==true)
                        {
                            limpiar();
                            $('#dataTableStyle').append(fila);
                            $("#total").html("Bs. "+calcular_total().toFixed(2));
                            $("#totals").val(calcular_total().toFixed(2));
                            $("#montofactura").val(calcular_total().toFixed(2));

                            $('#btn_guardar').removeAttr('disabled');

                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Artículo Agregado',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Rellene los Campos Correctamente.',
                        // footer: '<a href="">Why do I have this issue?</a>'
                    })
                }

            }        
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
            }


            function eliminar(index)
            {
                // total=total-subtotal[index];
                // $("#total").html("Bs/." + total);
                $("#fila" + index).remove();
                $("#total").html("Bs. "+calcular_total().toFixed(2));
                $("#totals").val(calcular_total().toFixed(2));
                $("#montofactura").val(calcular_total().toFixed(2));

                if(calcular_total().toFixed(2) == 0)
                {
                    $('#btn_guardar').attr('disabled', true);
                }
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


            
            function unidad_administrativa()
            {
                var id =  $(this).val();    
                // alert(id)
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
            
        
            function onselect_proveedor_llenar()
            {
                datoProveedor = document.getElementById('provider').value.split('_');
                // alert(datoProveedor[2]);
                $("#provider_id").val(datoProveedor[0]);
                $("#nit").val(datoProveedor[1]);
                $("#responsable").val(datoProveedor[2]);
            }

            function onselect_tipo()
            {
                
                var tipo =  $(this).val();   
                var html_factura = '';
                if(tipo == 'Electronica')
                {
                    html_nroautorizacion =  '<div class="form-group">'
                        html_nroautorizacion+=       '<div class="form-line">'
                        html_nroautorizacion+=            '<input type="text" name="nroautorizacion" placeholder="Introducir Nro Autorizacion" class="form-control text" title="Introducir Nro de Autorización">'
                        html_nroautorizacion+=       '</div>'
                        html_nroautorizacion+=       '<small>Nro Autorizacion Fact.</small>'
                        html_nroautorizacion+=  '</div>'
                    $('#nroautorizacion').html(html_nroautorizacion);

                    html_factura =  '<div class="form-group">'
                    html_factura+=       '<div class="form-line">'
                    html_factura+=            '<input type="text" name="nrocontrol" class="form-control text" placeholder="Introducir Nro Control" title="Introducir Nro Control Factura">'
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
                        html_nroautorizacion+=            '<input type="text" name="nroautorizacion" class="form-control text" placeholder="Introducir Nro Autorizacion" title="Introducir Nro de Autorización">'
                        html_nroautorizacion+=       '</div>'
                        html_nroautorizacion+=       '<small>Nro Autorizacion Fact.</small>'
                        html_nroautorizacion+=  '</div>'
                        $('#nroautorizacion').html(html_nroautorizacion);

                        html_factura ='';
                        $('#nrocontrol').html(html_factura);
                    }
                    
                }
            }

            function onselect_article()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_article')}}/'+id, function(data){
                        var html_article=    '<option value="" selected disabled>--Seleccione un Artículo--</option>'
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
    <script type="text/javascript">
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
            }else{
                if(key == 8 || key == 13 || key == 0) {     
                    return true;              
                }else if(key == 46){
                        if(filter(tempValue)=== false){
                            return false;
                        }else{       
                            return true;
                        }
                }else{
                    return false;
                }
            }
        }
        function filter(__val__){
            var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
            if(preg.test(__val__) === true){
                return true;
            }else{
            return false;
            }
            
        }
    </script>
    @stop

    @else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif