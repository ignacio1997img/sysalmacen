{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
@extends('voyager::master')

@section('page_title', 'Viendo Solicitud de Egreso')

<style>
    input:focus {
  background: rgb(197, 252, 215);
}
</style>
@if(auth()->user()->hasPermission('add_solicitud'))
    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title">
                    <i class="voyager-basket"></i> Crear Solicitud
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
                                        {!! Form::open(['route' => 'solicitud.store', 'class' => 'was-validated'])!!}
                                        <div class="card-body">
                                            <h5>Solicitud de Egreso</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="unidad" name="unidadadministrativa" class="form-control select2" required>
                                                                <option value="">Seleccione su Unidad..</option>
                                                                @foreach($unidad as $data)
                                                                    <option value="{{$data->id}}">{{$data->unidad}}</option>                                                                
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Unidad Administrativa.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" name="fechasolicitud" class="form-control" required>
                                                        </div>
                                                        <small>Fecha Solicitud.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" disabled class="form-control form-control-sm" value="{{$nroproceso}}" placeholder="Introducir número de solicitud" autocomplete="off" required>
                                                            <input type="hidden" name="nroproceso" value="{{$nroproceso}}">
                                                        </div>
                                                        <small>Número Proceso.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="modalidad" name="solicitudcompra_id" class="form-control select2" required>
                                                            
                                                                
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Modalidad de Compra.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <h5>Articulo:</h5>
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
                                                            <input type="text" id="presentacion" class="form-control form-control-sm" placeholder="Seleccione un Articulo" readonly>
                                                        </div>
                                                        <small>Presentación.</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="stok" class="form-control form-control-sm" placeholder="Seleccione un Articulo" readonly>
                                                        </div>
                                                        <small>Stock Artículo *(Disponible).</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="cantidad" step="0.01" class="form-control form-control-sm" placeholder="Introducir monto" autocomplete="off">
                                                        </div>
                                                        <small>Cantidad.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="hidden" id="precio" class="form-control form-control-sm" placeholder="Seleccione un Articulo" readonly>
                                                        </div>
                                                        <!-- <small>Precio.</small> -->
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
                                                        <!-- <th>Precio Art.</th>
                                                        <th>SubTotal</th> -->
                    
                                                    </tr>
                                                </thead>
                                                <!-- <tfoot>
                                                    <th colspan="7" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4 id="total">Bs. 0.00</h4></th>
                                                </tfoot> -->
                                                
                                            </table>
                                            
                                        </div>   
                                        <div class="card-footer">
                                            <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="voyager-logbook"></i> Guardar</button>
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
                // alert(4);
                $('#unidad').on('change', modalidad_compra);
                $('#modalidad').on('change', articulo);
                $('#article_id').on('change', llenar_input);


                $('#bt_add').click(function() {
                    agregar();
                });
            

            })


            var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
                // montofactura=$("#montofactura").val();
                modalidad=$("#modalidad option:selected").text();
                solicitudcompra_id =$("#modalidad").val();
                detallefactura_id =$("#article_id").val();
                presentacion=$("#presentacion").val();
                precio=$("#precio").val();
                stok=$("#stok").val();
                cantidad=parseFloat($("#cantidad").val());

                nombre_articulo=$("#article_id option:selected").text();

                var arrayarticle = [];
                var i=0;
                ok=false;

                // alert(detallefactura_id);
                // alert(stok);

                

                if (nombre_articulo != '' && nombre_articulo != 'Seleccione un Articulo..' && cantidad != '') {
                    
                        // alert(2);
                    
                        var fila='<tr class="selected" id="fila'+cont+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td><input type="hidden" class="input_article" name="detallefactura_id[]"value="'+detallefactura_id+'">'+detallefactura_id+'</td>' 
                            fila+='<td><input type="hidden" name="solicitudcompra_id[]"value="'+solicitudcompra_id+'">'+modalidad+'</td>'                         
                            fila+='<td>'+nombre_articulo+'</td>'
                            fila+='<td>'+presentacion+'</td>' 
                            fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'                       
                            // fila+='<td>'+precio+'</td>'                        
                            // fila+='<td><input type="hidden" class="input_subtotal" name="totalbs[]" value="'+cantidad * precio+'"></td>'
                        fila+='</tr>';


                        let detalle_subtotal = parseFloat(calcular_total()+cantidad * precio ).toFixed(2);
                        if (cantidad >= 1 &&  cantidad <= stok && stok != 0 ) {

                            cont++;
                            
                            limpiar();
                            $('#detalles').append(fila);
                            $("#total").html("Bs. "+calcular_total().toFixed(2));
                        
                            $(".input_article").each(function(){
                                arrayarticle[i]= parseFloat($(this).val());
                                // alert(arrayarticle[i]);
                                i++;
                            }); 

                            for(j=0;j<arrayarticle.length-1; j++)
                            {
                                // alert(arrayarticle[j])
                                if(arrayarticle[j] == arrayarticle[arrayarticle.length-1])
                                {
                                    eliminar(arrayarticle.length-1)
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
                            
                        }
                        else
                        {
                            // alert(total);
                            swal({
                                title: "Error",
                                text: "Cantidad de Stok seleccionada Incorrecta",
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
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
                $("#stok").val("");
                $("#presentacion").val("");
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






















            function modalidad_compra()
            {
                var id =  $(this).val();    
                // alert(3)
                if(id >=1)
                {
                    $.get('{{route('ajax_modalidadcompra')}}/'+id, function(data){
                        // alert(data);
                        var html_modalidad=    '<option value="">Seleccione Una Modalidad..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_modalidad += '<option value="'+data[i].id+'">'+data[i].nombre+' - '+data[i].nrosolicitud+'</option>'

                        $('#modalidad').html(html_modalidad);;            
                    });
                }
                else
                {
                    var html_modalidad=    ''       
                    $('#modalidad').html(html_modalidad);
                    var html_modalidad=    ''       
                    $('#article_id').html(html_modalidad);
                    $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');     
                }
            }

            function articulo()
            {
                var id =  $(this).val();    
                // alert(id)
                if(id >=1)
                {
                    $.get('{{route('ajax_solicitudes_articulo')}}/'+id, function(data){
                        // alert(data);
                        var html_modalidad=    '<option value="">Seleccione Un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_modalidad += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                        $('#article_id').html(html_modalidad);;            
                    });
                }
                else
                {
                    var html_modalidad=    ''       
                    $('#article_id').html(html_modalidad);
                    $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');     
                }
            }

            function llenar_input()
            {
                var id =  $(this).val();    
                
                if(id >=1)
                {
                    $.get('{{route('ajax_autollenar_articulo')}}/'+id, function(data){
                        $("#presentacion").val(data[0].presentacion);
                        $("#precio").val(data[0].precio);
                        $("#stok").val(data[0].cantrestante);
                        
                    });
                }
                else
                {
                    $("#presentacion").val('');
                    $("#stok").val('');
                    $("#precio").val('');                
                }
                
            }


        </script> 
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif