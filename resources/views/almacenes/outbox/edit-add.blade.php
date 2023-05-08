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
                                    <p><small>{{$user->direction->nombre}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <label class="panel-title">Unidad</label>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{$user->unit->nombre??'Sin Unidad'}}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>                            
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
                                                <th style="text-align: center; width: 120px">Cantidad</th>  
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
                                @if ($gestion && $user->unidadAdministrativa_id)
                                    <button type="submit" id="btn-register" class="btn btn-success btn-block">Registrar Pedido <i class="voyager-basket"></i></button>
                                @endif
                                <button id="btn-volver" class="btn btn-block" href="{{ route('outbox.index') }}" >Volver a la lista</button>
                            </div>                        
                            
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}      

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
            // $('#btn-register').text('Registrando...');
            // $('#btn-register').prop('disabled', true);

            $("#btn-register").text('Registrando...');
            $("#btn-register").attr('disabled','disabled');


            // $('#btn-volver').prop('disabled', true);
            $('#btn-volver').attr('disabled','disabled');
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
                                <input type="number" name="cantidad[]" min="0.1" step="0.01" id="select-cant-${product.id}" style="text-align: right" class="form-control text" required>
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

@stop
