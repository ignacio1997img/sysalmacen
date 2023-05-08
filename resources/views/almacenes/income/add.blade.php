@extends('voyager::master')

@section('page_title', 'Viendo Ingresos del almacen')


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
                            
                            {!! Form::open(['route' => 'income.store', 'id'=>'form-income', 'class' => 'was-validated'])!!}  
                                <input type="hidden" name="inventarioAlmacen_id" @if($gestion) value="{{$gestion->id}}" @endif>
                                <input type="hidden" name="gestion" @if($gestion) value="{{$gestion->gestion}}" @endif>

                                <h5>Solicitud de Compras</h5>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select name="branchoffice_id" class="form-control select2" required>
                                                    <option value="{{$sucursal->id}}" selected>{{$sucursal->nombre}}</option>
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
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select id="unidadEje" name="unidadadministrativa" class="form-control select2" required>
                                                
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
                                </div>
                                <hr>
                                <h5 id="subtitle">Proveedor + Detalle de Factura:</h5>
    
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="form-control" id="select_proveedor" name="provider_id"></select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" disabled title="Nuevo proveedor" data-target="#modal-create-customer" data-toggle="modal" style="margin: 0px" type="button">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </div>
                                            <small for="product_id">Proveedor</small>
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
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <select class="form-control" id="select_producto"></select>

                                                </span>
                                            </div>
                                            <small for="product_id">Artículo</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="dataTable" class="tables table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px">N&deg;</th>
                                                        <th style="text-align: center">Detalle</th>  
                                                        <th style="text-align: center; width: 120px">Cantidad Unit.</th>  
                                                        <th style="text-align: center; width: 120px">Precio Unit.</th>  
                                                        <th style="text-align: center; width: 120px">Sub Total</th>  
                                                        <th style="text-align: center; width: 80px"></th> 





                                                    </tr>
                                                </thead>
                                                <tbody id="table-body">
                                                    <tr id="tr-empty">
                                                        <td colspan="6" style="height: 290px">
                                                            <h4 class="text-center text-muted" style="margin-top: 50px">
                                                                <i class="voyager-basket" style="font-size: 50px"></i> <br><br>
                                                                Lista de artículo vacía
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr id="tr-empty">
                                                        <td colspan="3" style="text-align: right">
                                                            Total
                                                        </td>
                                                        <td colspan="2" style="text-align: right">
                                                            <small>Bs.</small> <b id="label-total">0.00</b>
                                                            <input type="hidden" name="amount" id="input-total" value="0">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div> 
                                </div>




                                @if (!$gestion)
                                    <div class="alert alert-danger">
                                        <strong>Aviso: </strong>
                                        <p> No tiene una gestion activa, contactese con los administradores del sistema.</p>
                                        </div> 
                                @endif

                                        
                                
                                @if ($gestion)
                                    <div class="card-footer">
                                        <button id="btn_guardar" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                    </div>   
                                @endif
                                {!! Form::close() !!}      


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        {{-- <input type="text" onkeypress="return NumCheck(event, this)"/>      --}}
        {{-- <input type="text" name="moneda nac" id="moneda_nac" value="10" onkeypress="return filterFloat(event,this);"/>    --}}

        {{-- Modal crear cliente --}}



    @stop


    @section('css')
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stop

    @section('javascript')
        {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        <script src="{{ asset('vendor/tippy/popper.min.js') }}"></script>
        <script src="{{ asset('vendor/tippy/tippy-bundle.umd.min.js') }}"></script>
        <script>
            var productSelected, customerSelected;
            $(document).ready(function(){
    
                $('#select_proveedor').select2({
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
                        url: "{{ url('admin/income/provider/list/ajax') }}",        
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
                        customerSelected = opt;
                        return opt.razonsocial;
                    }
                }).change(function(){
                });
            });
     
            function formatResultCustomers(option){
                // Si está cargando mostrar texto de carga
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                // Mostrar las opciones encontradas
                return $(`  <div>
                                <b style="font-size: 16px">${option.razonsocial}</b><br>
                                <small>NIT: ${option.nit ? option.nit : 'No definido'} - Cel: ${option.telefono ? option.telefono : 'No definido'}</small>
                            </div>`);
            }
        </script>


        <script>
            $(document).ready(function(){
                
                // $('#form-income').submit(function(e){

                //     $("#btn_guardar").text('Guardando......');
                //     $("#btn_guardar").attr('disabled','disabled');


                //     $('#btn-volver').attr('disabled','disabled');
                // });
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
                        url: "{{ url('admin/income/article/list/ajax') }}",        
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
                    templateResult: formatResultArticle,
                    templateSelection: (opt) => {
                        productSelected = opt;

                        
                        return opt.id?opt.article:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                    if($('#select_producto option:selected').val()){
                        let product = productSelected;
                        if($('.tables').find(`#tr-item-${product.id}`).val() === undefined){
                            $('#table-body').append(`
                                <tr class="tr-item" id="tr-item-${product.id}">
                                    <td class="td-item"></td>
                                    <td id="description-${product.id}">
                                        <b class="label-description"><small>${product.id} - ${product.article}</small><br>
                                        <b class="label-description"><small>PRESENTACION: </small>${product.presentacion}<br>
                                        <b class="label-description"><small>PARTIDA: </small>${product.codigo} - ${product.partida}
                                        <input type="hidden" name="article_id[]" value="${product.id}" />
                                    </td>
                                    <td>
                                        <input type="number" onkeypress="return filterFloat(event,this);" onkeyup="getSubtotal(${product.id})" onchange="getSubtotal(${product.id})" name="cantidad[]" min="0.01" step="0.01" id="input-quantity-${product.id}" style="text-align: right" class="form-control text" required>
                                    </td>
                                    <td>
                                        <input type="number" onkeypress="return filterFloat(event,this);" onkeyup="getSubtotal(${product.id})" onchange="getSubtotal(${product.id})" name="precio[]" min="0.01" step="0.01" id="input-price-${product.id}" style="text-align: right" class="form-control text" required>
                                    </td>
                                    <td class="text-right">
                                        <h4 class="label-subtotal" id="label-subtotal-${product.id}">0</h4>
                                        <input type="hidden" name="subT[]" id="input-subt-${product.id}">
                                    </td>

                                    <td class="text-right"><button type="button" onclick="removeTr(${product.id})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                                </tr>
                            `);



                            // popover
                            let image = "{{ asset('images/default.jpg') }}";
                            if(product.image){
                                image = "{{ asset('storage') }}/" + product.image.replace('.', '-cropped.');
                                
                            }                            

                            tippy(`#description-${product.id}`, {
                                content: `  <div style="display: flex; flex-direction: row">
                                                <div style="margin-right:10px">
                                                    <img src="${image}" width="60px" alt="${product.article}" />
                                                </div>
                                                <div>
                                                    <b>${product.article}</b><br>
                                                    <b>PRESENTACION : ${product.presentacion}</b><br>
                                                    <b>PARTIDA: ${product.partida}</b>    
                                                </div>
                                            </div>`,
                                allowHTML: true,
                                maxWidth: 450,
                            });


                            toastr.success('Producto agregado..', 'Información');

                        }else{
                            // alert(1)
                            toastr.warning('El detalle ya está agregado..', 'Información');
                        }
                        setNumber();
                        getSubtotal(product.id);
                    }
                });
                

            })

            function formatResultArticle(option){
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
                                    <b style="font-size: 14px"><small>${option.id} -</small> ${option.article} </b> <br>
                                    <small style="font-size: 12px">PRESENTACION:</small> ${option.presentacion}<br>
                                    <small style="font-size: 14px">PARTIDA: </small> ${option.codigo} - ${option.partida}
                                
                                </div>
                            </div>`);
            }

            function getSubtotal(id){
                // alert(id)
                let price = $(`#input-price-${id}`).val() ? parseFloat($(`#input-price-${id}`).val()) : 0;
                let quantity = $(`#input-quantity-${id}`).val() ? parseFloat($(`#input-quantity-${id}`).val()) : 0;
                // alert(price)
                $(`#label-subtotal-${id}`).text((price * quantity).toFixed(2));
                $(`#input-subt-${id}`).val((price * quantity).toFixed(2));
                getTotal();
            }

            function getTotal(){
                let total = 0;
                $(".label-subtotal").each(function(index) {
                    total += parseFloat($(this).text());
                });
                $('#montofactura').val(total.toFixed(2));
                $('#totals').val(total.toFixed(2));

                $('#label-total').text(total.toFixed(2));
                $('#input-total').val(total.toFixed(2));
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
                getTotal();
            }







        </script>















        <script>
            // $(document).ready(function(){
            //     $('#form-agregar').submit(function(e){
            //         // $('#btn_guardar').css('display', 'none');
            //         $('#btn_guardar').attr('disabled', true);

            //     });
            // })

            $(function()
            {    

                $('#das').on('change', unidad_administrativa);

                $('#tipofactura').on('change', onselect_tipo);

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



            // var cont=0;
            // var total=0;
            // subtotal=[];

            // function agregar()
            // {
            //     partida=$("#partida option:selected").text();
            //     // montofactura=$("#montofactura").val();
            //     article_id=$("#article_id").val();
            //     presentacion=$("#presentacion").val();

            //     cantidad=parseFloat($("#cantidad").val()?$("#cantidad").val():0).toFixed(2);
            //     precio=parseFloat($("#precio").val()? $("#precio").val():0).toFixed(2);

            //     subT = cantidad * precio;

            //     nombre_articulo=$("#article_id option:selected").text();

            //     var arrayarticle = [];
            //     var i=0;
            //     var j=0;
            //     ok=false;

            //     // alert(cantidad)

            //     if (partida != 'Seleccione una Partida..' && nombre_articulo != 'Seleccione un Articulo..' && cantidad > 0 && precio > 0) {
                    
                  

            //         var fila='<tr class="selected" id="fila'+article_id+'">'
            //                 fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+article_id+')";><i class="voyager-trash"></i></button></td>'
            //                 fila+='<td>'+partida+'</td>'
            //                 fila+='<td><input type="hidden" class="input_article" name="article_id[]"value="'+article_id+'">'+nombre_articulo+'</td>'
            //                 fila+='<td>'+presentacion+'</td>'
            //                 fila+='<td style="text-align: right"><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'
            //                 fila+='<td style="text-align: right"><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'
            //                 fila+='<td style="text-align: right"><input type="hidden" class="input_subtotal" name="subtotal[]" value="'+(subT).toFixed(2)+'">'+(subT).toFixed(2)+'</td>'
            //             fila+='</tr>';
                    
            //             $(".input_article").each(function(){
            //                 // alert(parseFloat($(this).val()))
            //                 arrayarticle[i]= parseFloat($(this).val());
            //                 i++;
            //             }); 
            //             var ok=true;
            //             // alert(arrayarticle.length)
            //             for(j=0;j<arrayarticle.length; j++)
            //             {
                            
            //                 if(arrayarticle[j] == article_id)
            //                 {
            //                     limpiar();
            //                     ok = false;

            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Oops...',
            //                         text: 'El artículo ya existe en la lista..',
            //                     })                             
            //                 }
            //             }
            //             if(ok==true)
            //             {
            //                 limpiar();
            //                 $('#dataTableStyle').append(fila);
            //                 $("#total").html("Bs. "+calcular_total().toFixed(2));
            //                 $("#totals").val(calcular_total().toFixed(2));
            //                 $("#montofactura").val(calcular_total().toFixed(2));

            //                 $('#btn_guardar').removeAttr('disabled');

            //                 Swal.fire({
            //                     position: 'top-end',
            //                     icon: 'success',
            //                     title: 'Artículo Agregado',
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 })
            //             }
            //     }
            //     else
            //     {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'Rellene los Campos Correctamente.',
            //             // footer: '<a href="">Why do I have this issue?</a>'
            //         })
            //     }

            // }        
            // function limpiar()
            // {
            //     $("#precio").val("");
            //     $("#cantidad").val("");
            // }


            // function eliminar(index)
            // {
            //     // total=total-subtotal[index];
            //     // $("#total").html("Bs/." + total);
            //     $("#fila" + index).remove();
            //     $("#total").html("Bs. "+calcular_total().toFixed(2));
            //     $("#totals").val(calcular_total().toFixed(2));
            //     $("#montofactura").val(calcular_total().toFixed(2));

            //     if(calcular_total().toFixed(2) == 0)
            //     {
            //         $('#btn_guardar').attr('disabled', true);
            //     }
            // }

            //calcular total de factura
            // function calcular_total()
            // {
            //     let total = 0;
            //     $(".input_subtotal").each(function(){
            //         total += parseFloat($(this).val());
            //         // alert(parseFloat($(this).val()));
            //     });
            //     // console.log(total);
                
            //     return total;
            // }


            
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