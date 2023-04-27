@extends('voyager::master')

@section('page_title', 'añadir solicitud')

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
                {!! Form::open(['route' => 'outbox.store', 'id'=>'form-registrar-pedido', 'class' => 'was-validated'])!!}  

                <input type="hidden" name="inventarioAlmacen_id" @if($gestion) value="{{$gestion->id}}" @endif>
                <input type="hidden" name="gestion" @if($gestion) value="{{$gestion->gestion}}" @endif>

                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding-bottom: 0px">
                            <div class="form-group col-md-12">
                                <label for="customer_id">Almacen:</label>                              

                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="sucursal_id" class="form-control select2" required>
                                            @if ($sucursal)
                                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>                                                    
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Solicitante</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{$funcionario->nombre}} - {{$funcionario->cargo}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="form-group col-md-4">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Fecha de Solicitud</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{date('d/m/Y')}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>

                            <div class="form-group col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Dirección</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{$funcionario->direccion}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Unidad</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{$funcionario->unidad??'Sin Unidad'}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                           
                            {{-- <div class="form-group col-md-4">
                                <div class="checkbox">
                                    <label><input type="checkbox" id="checkbox-proforma" name="proforma" value="1" required>Aceptar</label>
                                </div>
                            </div> --}}
                            {{-- <div class="form-group col-md-8">
                                <h2 class="text-right"><small>Total: Bs.</small> <b id="label-total">0.00</b></h2>
                                <input type="hidden" name="amount" id="input-total" value="0">
                            </div> --}}
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="product_id">Buscar producto</label>
                                    <select class="form-control" id="select_producto"></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="tables table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px">N&deg;</th>
                                                <th style="text-align: center">Detalle</th>  
                                                <th style="text-align: center; width: 80px">Cantidad</th>  
                                                <th style="text-align: center; width: 80px"></th>  
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <tr id="tr-empty">
                                                <td colspan="6" style="height: 290px">
                                                    <h4 class="text-center text-muted" style="margin-top: 50px">
                                                        <i class="voyager-basket" style="font-size: 50px"></i> <br><br>
                                                        Lista de pedido vacía
                                                    </h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>       
                            <div class="form-group col-md-12 text-center">
                                {{-- Tiene que tener una gestion activa y tenes una unidad agregada como funcionario --}}
                                @if ($gestion && $funcionario->unidad)
                                    <button type="submit" id="btn-register" class="btn btn-success btn-block">Registrar Pedido <i class="voyager-basket"></i></button>
                                @endif
                                <a id="btn-volver" href="{{ route('outbox.index') }}" >Volver a la lista</a>
                            </div>                        
                            
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}      

            </div>   
            


            {{-- <div class="row"> 
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">    
                            

                                    
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
                                {{-- <div class="col-md-6">
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
                                </div> --}}
                                {{-- <div class="col-md-6">
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

                                                            {{-- <input type="number" id="cantidad" step="0.01" class="form-control form-control-sm text" placeholder="Introducir monto" autocomplete="off"> 
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
                                    
                                        <table id="dataTableStyle" class="table-bordered table-striped table-sm">
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

                                        {{-- Tiene que tener una gestion activa y tenes una unidad agregada como funcionario 
                                        @if ($gestion && $funcionario->unidad)
                                            <div class="card-footer">
                                                <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                            </div> 
                                        @endif 
                                    
                           
                        </div>
                    </div>
                </div>--}}
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function(){
        $('#form-registrar-pedido').submit(function(e){
            $('#btn-register').text('Registrando...');
            $('#btn-register').prop('disabled', true);
            $('#btn-volver').prop('disabled', true);
        });
    })
    $(document).ready(function(){
        var productSelected;

        $('#select_producto').select2({
        // tags: true,
            placeholder: '<i class="fa fa-search"></i> Buscar...',
            escapeMarkup : function(markup) {
                return markup;
            },
            language: {
                inputTooShort: function (data) {
                    return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                },
                noResults: function () {
                    return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                }
            },
            quietMillis: 250,
            minimumInputLength: 2,
            ajax: {
                url: "{{ url('admin/outbox/article/stock/ajax') }}",        
                processResults: function (data) {
                    let results = [];
                    data.map(data =>{
                        results.push({
                            ...data,
                            disabled: false
                        });
                    });
                    return {
                        results
                    };
                },
                cache: true
            },
            templateResult: formatResultCustomers,
            templateSelection: (opt) => {
                productSelected = opt;

                
                return opt.id?opt.nombre:'<i class="fa fa-search"></i> Buscar... ';
            }
        }).change(function(){
            // alert(2)
            // alert($('#select_producto option:selected').val())
            if($('#select_producto option:selected').val()){
                let product = productSelected;
                // toastr.info('EL detalle ya está agregado', 'Información');

                // alert(product.article_id);
                if($('.tables').find(`#tr-item-${product.id}`).val() === undefined){
                // alert(product.name);

                    $('#table-body').append(`
                        <tr class="tr-item" id="tr-item-${product.id}">
                            <td class="td-item"></td>
                            <td>
                                <b class="label-description" id="description-${product.id}"><small>${product.nombre}</small><br>
                                <b class="label-description"><small>${product.presentacion}</small>
                                <input type="hidden" name="article_id[]" value="${product.id}" />
                            </td>
                            <td>
                                <input type="number" name="cantidad[]" min="1" step="1" id="select-cant-${product.id}" style="text-align: right" class="form-control text" required>
                            </td>
                            <td class="text-right"><button type="button" onclick="removeTr(${product.id})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                        </tr>
                    `);
                    toastr.success('Producto agregado..', 'Información');

                }else{
                    // alert(1)
                    toastr.warning('El detalle ya está agregado..', 'Información');
                }
                setNumber();
                // getSubtotal(product.article_id);
            }
        });
        

    })

    function formatResultCustomers(option){
    // Si está cargando mostrar texto de carga
    // alert(option.article.name)
        if (option.loading) {
            return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
        }
        let image = "{{ asset('images/default.jpg') }}";
        if(option.image){
            image = "{{ asset('storage') }}/"+option.image.replace('.', '-cropped.');
            // alert(image)
        }
        
        // Mostrar las opciones encontradas
        return $(`  <div style="display: flex">
                        <div style="margin: 0px 10px">
                            <img src="${image}" width="50px" />
                        </div>
                        <div>
                            <b style="font-size: 16px"> ${option.nombre} </b> <br>
                            <small style="font-size: 16px">${option.presentacion} </small>
                         
                        </div>
                    </div>`);
    }



    function setNumber(){
        var length = 0;
        $(".td-item").each(function(index) {
            $(this).text(index +1);
            length++;
        });

        if(length > 0){
            $('#tr-empty').css('display', 'none');
        }else{
            $('#tr-empty').fadeIn('fast');
        }
    }

    function removeTr(id){
        // alert(1)
        $(`#tr-item-${id}`).remove();
        $('#select_producto').val("").trigger("change");
        setNumber();
        // getTotal();
    }







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

<script>
        $(function()
        {             
            // $(".select2").select2({theme: "classic"});u

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
