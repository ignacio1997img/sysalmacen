{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')
@if(auth()->user()->hasPermission('add_incomedonor'))

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


    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title" id="subtitle">
                    <i class="voyager-basket"></i> Añadir Ingreso de Donaciones
                </h1>
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
                                        {!! Form::open(['route' => 'incomedonor.store', 'class' => 'was-validated', 'enctype' => 'multipart/form-data'])!!}
                                        <div class="card-body">
                                            <h5 id="subtitle">Centro de Establecimiento</h5>
                                            <div class="row">
                                                <!-- === -->
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="centrotipo" class="form-control select2" required>
                                                                <option value="">Seleccione una opcion</option>
                                                                @foreach($centrotipo as $data)
                                                                    <option value="{{$data->id}}">{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Tipo.</small>
                                                    </div>
                                                </div>                                            
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="centro_id" name="centro_id" class="form-control select2" required>
                                                                
                                                            </select>
                                                        </div>
                                                        <small>Centro.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date"  class="form-control text" name="fechadonacion" required>
                                                        </div>
                                                        <small>Fecha de Donación.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" class="form-control text" name="fechaingreso" required>
                                                        </div>
                                                        <small>Fecha de Ingreso.</small>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <!-- <input type="date"  class="form-control" name="fechaingreso" required> -->
                                                            <textarea name="observacion" id="observacion" rows="2" class="form-control text"></textarea>
                                                        </div>
                                                        <small>Observación.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                    <div class="form-group">
                                                        <input type="file" name="archivos[]"  multiple class="form-control" accept="image/*">
                                                        <small>Archivos.</small>
                                                    </div>
                                            </div>
                                           
                                            <hr>
                                            <h5 id="subtitle">Donador:</h5>                                               
    
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select name="tipodonante" id="tipodonante"class="form-control select2" required>
                                                                <option value="">Seleccione un tipo..</option>
                                                                <option value="0">Empresa</option>
                                                                <option value="1">ONG</option>
                                                                <option value="2">Persona</option>           
                                                                <option value="3">Anónimo</option>           
                                                            </select>
                                                        </div>
                                                        <small>Tipos Donadores.</small>
                                                    </div>
                                                </div>
                                                
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <select id="donante" class="form-control select2" required>
                                                                    
                                                                    
                                                                </select>
                                                            </div>
                                                            <small>Seleccionar un Donante.</small>
                                                        </div>
                                                    </div>      
                                                    <input type="hidden" id="donante_id" name="donante_id">                                          
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" id="nit" class="form-control text form-control-sm" placeholder="Seleccione un donante" disabled readonly>
                                                            </div>
                                                            <small>CI./NIT.</small>
                                                        </div>
                                                    </div>
                                           
                                            </div>
                                            
                                            <hr>
                                            <h5 id="subtitle">Detalle Categoria / Articulo:</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="categoria"class="form-control select2">
                                                                <option value="">Seleccione una categoria..</option>
                                                                @foreach($categoria as $data)
                                                                    <option value="{{$data->id}}">{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Categoria.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select id="articulo" class="form-control select2">
                                                            
                                                        </select>
                                                        <small>Articulo.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- === -->
                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text"disabled class="form-control text form-control-sm" id="presentacion" autocomplete="off">
                                                        </div>
                                                        <small>Presentacion.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="cantidad" min="1" pattern="^[0-9]+" class="form-control text" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad Artículo.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <!-- <input type="number" id="precio" min="0" class="form-control positive" title="Precio Unitario Bs"> -->
                                                            <input type="number" id="precio" min="0" class="form-control text" title="Precio Unitario Bs">
                                                        </div>
                                                        <small>Precio Unit. Estimado *(Bs).</small>
                                                    </div>
                                                </div><div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" id="caducidad" class="form-control text" title="Fecha de caducidad del producto o articulo">
                                                        </div>
                                                        <small>Fecha Caducidad *(Opcional).</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="estado"class="form-control select2" required>
                                                                <option value="Exelente">Exelente</option>
                                                                <option value="Buena">Buena</option>
                                                                <option value="Regular">Regular</option>           
                                                                <option value="Mala">Mala</option>           
                                                            </select>
                                                        </div>
                                                        <small>Estado Producto.</small>
                                                    </div>
                                                </div>   
                                                <div class="col-sm-9">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <!-- <input type="date"  class="form-control" name="fechaingreso" required> -->
                                                            <textarea id="caracteristica" rows="2" class="form-control text" placeholder="Opcional"></textarea>
                                                        </div>
                                                        <small>Observación / Características.</small>
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
                                                        <th>Categoria</th>
                                                        <th>Artículo</th>
                                                        <th>Presentación</th>
                                                        <th>Estado</th>
                                                        <th>Característica</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio Estimado.</th>
                                                        <th>Fecha Caducidad.</th>
                                                        <th>SubTotal</th>
                    
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <th colspan="9" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4 id="total">Bs. 0.00</h4></th>
                                                </tfoot>
                                                
                                            </table>
                                           
                                            
                                        </div>   
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

            $(function()
            {    
                
                $(".select2").select2({theme: "classic"});
                // $(".select2").select2({theme: "classic"});
                $('#categoria').on('change', onselect_article);
                $('#articulo').on('change', onselect_presentacion);

                $('#centrotipo').on('change', onselect_centros);
                
                $('#tipodonante').on('change', onselect_donante);

                $('#donante').on('change', onselect_donante_llenar);


                $('#bt_add').click(function() {
                    agregar();
                });


                // $('#donante').select2();
            })

            // var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
                
                categoria=$("#categoria option:selected").text();
                nombre_articulo=$("#articulo option:selected").text();

                estado=$("#estado option:selected").text();
                caracteristica=$("#caracteristica").val();

                articulo_id =$("#articulo").val();
                presentacion=$("#presentacion").val();
                precio=parseFloat($("#precio").val());
                cantidad=parseFloat($("#cantidad").val());
                caducidad=$("#caducidad").val();
                // caducidad=$("#caducidad").val();

                auxprecio=$("#precio").val();
                auxcantidad=$("#cantidad").val();


                var arrayarticle = [];
                var i=0;
                var j=0;
                ok=false;

                if(caracteristica=="")
                {
                    caracteristica='S/N'
                }
                
                var auxcaducidad=''
                if(caducidad=="")
                {
                    auxcaducidad='S/N'
                }
                else
                {
                    auxcaducidad =caducidad;
                }
     

                if (categoria != 'Seleccione una categoria..' && nombre_articulo != 'Seleccione un Articulo..' && auxprecio != '' && auxcantidad != '' && cantidad > 0 && precio >= 0) {

               
                        var fila='<tr class="selected" id="fila'+articulo_id+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+articulo_id+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td>'+categoria+'</td>'
                            fila+='<td><input type="hidden" class="input_article" name="articulo_id[]"value="'+articulo_id+'">'+nombre_articulo+'</td>' 
                            fila+='<td>'+presentacion+'</td>' 
                            fila+='<td><input type="hidden" name="estado[]" value="'+estado+'">'+estado+'</td>' 
                            fila+='<td><input type="hidden" name="caracteristica[]" value="'+caracteristica+'">'+caracteristica+'</td>' 
                            fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'                       
                            fila+='<td><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'                        
                            fila+='<td><input type="hidden" name="caducidad[]" value="'+caducidad+'">'+auxcaducidad+'</td>'                        
                            fila+='<td><input type="hidden" class="input_subtotal" value="'+cantidad * precio+'">'+cantidad * precio+'</td>'
                        fila+='</tr>';

                        $(".input_article").each(function(){
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;
                        }); 
                        var ok=true;
                        for(j=0;j<arrayarticle.length; j++)
                        {
                            if(arrayarticle[j] == articulo_id)
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
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
                $("#caducidad").val("");
                $("#caracteristica").val("");

                // $("#presentacion").val("");
            }

            //eliminar filas en la tabla
            function eliminar(index)
            {
                // total=total-subtotal[index];
                // $("#total").html("Bs/." + total);
                $("#fila" + index).remove();
                $("#total").html("Bs. "+calcular_total().toFixed(2));
                // evaluar();
                // $('#btn_guardar').attr('disabled', true);
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

            // $(".positive").keyup(function () {
               
            // var valor = $(this).prop("value");
            // alert(valor)
            // //evaluamos si es negativo, y ponemos 1 por defecto
            // if (valor >= 0)
            //     $(value).val(0);
            // })



            // function validarNumero(value) {
            //     var valor = $(value).val();
            //     toString($(value).val());
            //     // alert(isNaN(valor));

            //     if (valor >= 0){
            //         $(value).val(valor);
            //     }
            //     else{
            //         $(value).val(0);
            //     }
            // }































            

            function onselect_article()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_article_donor')}}/'+id, function(data){
                        var html_article=    '<option value="">Seleccione un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_article += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#articulo').html(html_article);           
                    });
                }
                else
                {
                    var html_article=    ''       
                    $('#articulo').html(html_article);
                    // $("#presentacion").val('');
                }
            }

            function onselect_presentacion()
            {
                var id =  $(this).val();    
                if(id >=1)
                {

                    $.get('{{route('ajax_presentacion_donor')}}/'+id, function(data){
                        $("#presentacion").val(data.presentacion);
                    });
                }
                else
                {
                    $("#presentacion").val('');
                    
                }
            }

            function onselect_centros()
            {
                var id =  $(this).val();    
                if(id >=1)
                {

                    $.get('{{route('ajax_centro_acogida')}}/'+id, function(data){
                        var html_centro=    '<option value="">Seleccione un centro..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_centro += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#centro_id').html(html_centro);           
                    });
                }
                else
                {
                    var html_centro=    ''       
                    $('#centro_id').html(html_centro);
                }
            }

            function onselect_donante()
            {
                var id =  $(this).val();    

                if(id >=0 && id != "")
                {
                    $("#nit").val(""); 
                    if(id!=3)   
                    {
                        $.get('{{route('ajax_income_donante')}}/'+id, function(data){
                            var html_donante=    '<option value="">Seleccione una opción..</option>'
                                for(var i=0; i<data.length; ++i)
                                html_donante += '<option value="'+data[i].id+'_'+data[i].ci+'">'+data[i].nombre+'</option>'

                            $('#donante').html(html_donante);           
                        });
                    }  
                    else
                    {
                        var html_donante= '<option value="'+0000+'">Anónimo</option>';
                        $('#donante').html(html_donante); 
                        $("#nit").val("XXXXXXXXXX");
                    }                 
                }
                else
                {
                    var html_donante=    ''       
                    $('#donante').html(html_donante);
                    $("#donador_id").val("");
                    $("#nit").val("");
                }
            }
            

            // function div_donacione_show(x)
            // {
            //     var html_donador = '';

            //             html_donador+=      '<div class="col-sm-6">'
            //             html_donador+=          '<div class="form-group">'
            //             html_donador+=              '<div class="form-line">'
            //             html_donador+=                   '<select id="donante" class="form-control select2" required>' 
            //             if(x==3)      
            //             {
            //                 html_donador+=                        '<option value="">Anónimo</option>'   
            //             }  
            //             html_donador+=                   '</select>'
            //             html_donador+=              '</div>'
            //             html_donador+=              '<small>Seleccionar un Donante.</small>'
            //             html_donador+=          '</div>'
            //             html_donador+=      '</div>'
            //             if(x==0 && x==1)   
            //             {
            //                 html_donador+=      '<input type="hidden" id="donante_id" name="onuempresa_id">'
            //             }
            //             if(x==2) 
            //             {
            //                 html_donador+=      '<input type="hidden" id="donante_id" name="persona_id">'
            //             }               
                                                
            //             html_donador+=      '<div class="col-sm-3">'
            //             html_donador+=           '<div class="form-group">'
            //             html_donador+=                '<div class="form-line">'
            //             if(x==3)
            //             {
            //                 html_donador+=                    '<input type="text" id="nit" class="form-control form-control-sm" value="XXXXXXXX" disabled readonly>'

            //             }
            //             else
            //             {
            //                 html_donador+=                    '<input type="text" id="nit" class="form-control form-control-sm" placeholder="Seleccione un donante" disabled readonly>'
            //             }
            //             html_donador+=                '</div>'
            //             html_donador+=                '<small>CI./NIT.</small>'
            //             html_donador+=           '</div>'
            //             html_donador+=      '</div>'
                
            //     $('#donante').select2();

            //     $('#div_donadores').html(html_donador);
            //     $('#donante').on('change', onselect_donante_llenar);

            // }

            function onselect_donante_llenar()
            {
                donador = document.getElementById('donante').value.split('_');
 
                $("#donante_id").val(donador[0]);
                $("#nit").val(donador[1]);
            }



        </script> 
    @stop



    @else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif