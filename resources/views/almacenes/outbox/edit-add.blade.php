@extends('voyager::master')

@section('page_title', 'añadir Egresos')

@section('page_header')
    
    <div class="container-fluid">
        <div class="row">
            <h1 id="subtitle" class="page-title">
                <i class="voyager-basket"></i> Crear Solicitud
            </h1>
            <a href="{{ route('outbox.index') }}" class="btn btn-warning btn-add-new">
                <i class="fa-solid fa-file"></i> <span>Volver</span>
            </a>
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
                            {!! Form::open(['route' => 'outbox.store', 'class' => 'was-validated'])!!}  

                            <input type="hidden" name="inventarioAlmacen_id" @if($gestion) value="{{$gestion->id}}" @endif>
                            <input type="hidden" name="gestion" @if($gestion) value="{{$gestion->gestion}}" @endif>

                                    
                            <h5 id="subtitle">Solicitud de Compras</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="sucursal_id" class="form-control select2" required>
                                                @if ($sucursal)
                                                    <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>                                                    
                                                @endif
                                            </select>
                                        </div>
                                        <small>Almacen Correspondiente.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Solicitante</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$funcionario->nombre}} - {{$funcionario->cargo}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Fecha de Solicitud</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{date('d/m/Y')}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Dirección</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$funcionario->direccion}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Unidad</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$funcionario->unidad??'Sin Unidad'}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                               
                            </div>
                            <h5 id="subtitle">Articulo:</h5>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select id="article_id" class="form-control select2">
                                            <option disabled selected>--Seleccione una opción--</option>
                                            @foreach ($data as $item)
                                                <option value="{{$item->article_id}}">{{$item->article}}</option>
                                            @endforeach
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
                                                            <input type="number" id="cantidad" min="0" step=".1" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" style="text-align: right" class="form-control text">                                    

                                                            {{-- <input type="number" id="cantidad" step="0.01" class="form-control form-control-sm text" placeholder="Introducir monto" autocomplete="off"> --}}
                                                        </div>
                                                        <small>Cantidad a solicitar.</small>
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
                                                    <th style="width: 5px; text-align: center">Opciones</th>
                                                    <th style="width: 50px; text-align: center">Id</th>                                                 
                                                    <th style="text-align: center">Articulo</th>
                                                    <th style="text-align: center">Presentación</th>
                                                    <th style="text-align: center">Cantidad</th>                
                                                </tr>
                                            </thead>                                            
                                        </table>

                                        {{-- Tiene que tener una gestion activa y tenes una unidad agregada como funcionario --}}
                                        @if ($gestion && $funcionario->unidad)
                                            <div class="card-footer">
                                                <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                            </div> 
                                        @endif 
                                    
                                    {!! Form::close() !!}      
                           
                        </div>
                    </div>
                </div>
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
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<script>
        $(function()
        {             
            $(".select2").select2({theme: "classic"});

            $('#article_id').on('change', onselect_presentacion);

            $('#bt_add').click(function() {
		    	agregar();
		  	});           
        })
       


        var cont=0;
        var total=0;
		subtotal=[];

        function agregar()
        {
            presentacion=$("#presentacion").val();


            article_id =$("#article_id").val()?$("#article_id").val():0;

            let cantidad = $(`#cantidad`).val() ? parseFloat($(`#cantidad`).val()) : 0;


		    nombre_articulo=$("#article_id option:selected").text();

            var arrayarticle = [];
            var i=0;
            ok=false;

            if (article_id != 0 && cantidad != 0) {
                    var fila='<tr class="selected" id="fila'+article_id+'">'
                        fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+article_id+')";><i class="voyager-trash"></i></button></td>'
                        fila+='<td><input type="hidden" class="input_article" name="article_id[]" value="'+article_id+'">'+article_id+'</td>'                         
                        fila+='<td>'+nombre_articulo+'</td>'
                        fila+='<td style="text-align: center">'+presentacion+'</td>' 
                        fila+='<td style="text-align: right"><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'       
                    fila+='</tr>';

                    $(".input_article").each(function(){
                        arrayarticle[i]= parseFloat($(this).val());
                        i++;
                    }); 
                    var ok=true;
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
                })
            }

        }        
        
            function limpiar()
            {
                $("#cantidad").val("");
            }


            function eliminar(index)
            {
                $("#fila" + index).remove();

               
            }

      





        function onselect_presentacion()
        {            
            var id =  $(this).val();    
            if(id >=1)
            {
                $.get('{{route('ajax_egres_select_article_detalle')}}/'+id, function(data){
                    $("#presentacion").val(data.presentacion);                                
                });
            }
            else
            {
                $("#presentacion").val('');
            }            
        }

        


    </script> 
@stop
