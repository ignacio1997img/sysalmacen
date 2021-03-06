@extends('voyager::master')

@section('page_title', 'Registrar Salida Donacion')

<style>
    input:focus {
  background: rgb(197, 252, 215);
}
</style>


<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>

@if(auth()->user()->hasPermission('add_egressdonor'))

    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title" id="subtitle">
                    <i class="voyager-basket"></i> Añadir Egreso de Donaciones
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
                                        {!! Form::open(['route' => 'egressdonor.store', 'class' => 'was-validated', 'enctype' => 'multipart/form-data'])!!}
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
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date"  class="form-control text" name="fechaentrega" required>
                                                        </div>
                                                        <small>Fecha de Donación.</small>
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
                                                        <small>Observacion.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                    <div class="form-group">
                                                        <input type="file" name="archivos[]"  multiple class="form-control text" accept="image/*">
                                                        <small>Archivos.</small>
                                                    </div>
                                            </div>
                                            
                                            <h5 id="subtitle">Categoria / Articulo:</h5>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="ingreso"class="form-control select2">
                                                                <option value="">Seleccione una categoria..</option>
                                                                @foreach($vigente as $data)
                                                                    <option value="{{$data->id}}">{{$data->nrosolicitud}} - {{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Ingreso Disponible.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">                                               
                                                
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select id="articulos" class="form-control select2">
                                                            
                                                        </select>
                                                        <small>Articulo.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="stock" disabled class="form-control text" title="Stock">
                                                        </div>
                                                        <small>Cantidad (STOCK).</small>
                                                    </div>
                                                </div>     
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="cantidad"  class="form-control text" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad Artículo.</small>
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
                                                        <th>Articulo</th>
                                                        <th>Estado</th>
                                                        <th>Característica</th>
                                                        <th>Cantidad</th>
                    
                                                    </tr>
                                                </thead>
                                                <!-- <tfoot>
                                                    <th colspan="7" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4 id="total">Bs. 0.00</h4></th>
                                                </tfoot> -->
                                                
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

        
            small{font-size: 12px;
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
        </style>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stop

    @section('javascript')
    
        <script>

            $(function()
            {    
                $(".select2").select2({theme: "classic"});
                $('#ingreso').on('change', onselect_article);
                $('#articulos').on('change', llenar_input);
                // $('#articulo').on('change', onselect_presentacion);

                $('#centrotipo').on('change', onselect_centros);
                
                // $('#tipodonante').on('change', onselect_donante);

                // $('#donante').on('change', onselect_donante_llenar);


                $('#bt_add').click(function() {
                    agregar();
                });

            })

            var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
                
                // categoria=$("#categoria option:selected").text();
                nombre_articulo=$("#articulos option:selected").text();
                articulo_id =$("#articulos").val();
                // presentacion=$("#presentacion").val();
                estado=$("#estado option:selected").text();
                caracteristica=$("#caracteristica").val();

                cantidad=parseFloat($("#cantidad").val());
                // cantidad=$("#cantidad").val();
                stock=$("#stock").val();

                    // alert(cantidad);

                auxcantidad=$("#cantidad").val();


                var arrayarticle = [];
                var i=0;
                var j=0;
                ok=false;


                if(caracteristica=="")
                {
                    caracteristica='S/N'
                }
            

                if (nombre_articulo != 'Seleccione un Articulo..' && auxcantidad != '' && cantidad > 0) {
       
                        var fila='<tr class="selected" id="fila'+articulo_id+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+articulo_id+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td><input type="hidden" class="input_article" name="articulo_id[]"value="'+articulo_id+'">'+nombre_articulo+'</td>' 
                            fila+='<td><input type="hidden" name="estado[]" value="'+estado+'">'+estado+'</td>' 
                            fila+='<td><input type="hidden" name="caracteristica[]" value="'+caracteristica+'">'+caracteristica+'</td>' 
                            fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'                       
                        fila+='</tr>';

                        $(".input_article").each(function(){
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;
                        }); 
                        var ok=true;

                        for(j=0;j<arrayarticle.length; j++)
                        {
                            // alert(arrayarticle[j])
                            if(arrayarticle[j] == articulo_id)
                            {
                                // cont--;
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
                            if (cantidad >= 1 &&  cantidad <= stock  )
                            {
                                limpiar();
                                $('#detalles').append(fila);
                                $("#total").html("Bs. "+calcular_total().toFixed(2));
                            }
                            else
                            {
                                // alert(total);
                                swal({
                                    title: "Error",
                                    text: "La cantidad solicitada supera a la cantidad disponible",
                                    type: "error",
                                    showCancelButton: false,
                                    });
                                div = document.getElementById('flotante');
                                div.style.display = '';
                                return;
                            }
                        }





                        // if (cantidad >= 1 &&  cantidad <= stock  ) {
                        //     cont++;
                        //     alert(45)
                        //     limpiar();
                        //     $('#detalles').append(fila);
                        //     $("#total").html("Bs. "+calcular_total().toFixed(2));
                        
                        //     $(".input_article").each(function(){
                        //         arrayarticle[i]= parseFloat($(this).val());
                        //         i++;
                        //     }); 

                        //     for(j=0;j<arrayarticle.length-1; j++)
                        //     {
                        //         if(arrayarticle[j] == arrayarticle[arrayarticle.length-1])
                        //         {
                        //             cont--;
                        //             eliminar(arrayarticle.length-1)
                        //             swal({
                        //                 title: "Error",
                        //                 text: "El Articulo ya Existe en la Lista",
                        //                 type: "error",
                        //                 showCancelButton: false,
                        //                 });
                        //             div = document.getElementById('flotante');
                        //             div.style.display = '';
                        //             return;
                                    
                        //         }
                        //     }
                        // }
                        // else
                        // {
                        //     // alert(total);
                        //     swal({
                        //         title: "Error",
                        //         text: "La cantidad solicitada supera a la cantidad disponible",
                        //         type: "error",
                        //         showCancelButton: false,
                        //         });
                        //     div = document.getElementById('flotante');
                        //     div.style.display = '';
                        //     return;
                        // }
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
                // $("#stock").val("");
                $("#cantidad").val("");
                $("#caracteristica").val("");
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


















            function onselect_centros()
            {
                $("#stock").val("");
                $("#cantidad").val("");

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

            function onselect_article()
            {
                $("#stock").val("");
                $("#cantidad").val("");

                var id =  $(this).val();    
                if(id >=1)
                {                    
                    $.get('{{route('ajax_disponible_article_donor')}}/'+id, function(data){
                        var html_article=    '<option value="">Seleccione un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_article += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#articulos').html(html_article);           
                    });
                }
                else
                {
                    var html_article=    ''       
                    $('#articulos').html(html_article);
                    // $("#presentacion").val('');
                }
            }

            function llenar_input()
            {
                var id =  $(this).val();    
                
                if(id >=1)
                {
                    $.get('{{route('ajax_egressdoner_llenarimput')}}/'+id, function(data){
                        // $("#presentacion").val(data[0].presentacion);
                        // $("#precio").val(data[0].precio);
                        $("#stock").val(data[0].cantrestante);
                        
                    });
                }
                else
                {
                    $("#stock").val('');            
                }
                
            }



        </script> 
    @stop
    @section('content')
        <h1>No tienes permiso</h1>
        <br>
        <h1>Contactese con el Administrador del sistema</h1>
    @stop
@endif
