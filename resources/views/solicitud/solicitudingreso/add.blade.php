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
</style>

@if(auth()->user()->hasPermission('add_income'))

    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title">
                    <i class="voyager-basket"></i> Solicitud Pedido
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
                                        {!! Form::open(['route' => 'income.store', 'class' => 'was-validated'])!!}
                                        <div class="card-body">
                                            <h5>Partida + Articulo:</h5>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <select id="article_id" class="form-control select2">
                                                            <option value="">Seleccione un articulo...</option>
                                                            @foreach($articulo as $item)
                                                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <small>Articulo.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- === -->
                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text"disabled class="form-control form-control-sm" id="presentacion" autocomplete="off">
                                                        </div>
                                                        <small>Presentacion.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="cantidad" min="1" class="form-control" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad Artículo.</small>
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
                                                <tfoot>
                                                    <th colspan="6" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4 id="total">Bs. 0.00</h4></th>
                                                </tfoot>
                                                
                                            </table>
                                            
                                        </div>   
                                        <div class="card-footer">
                                            <button id="btn_guardar" disabled type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                        </div>   
                                                     
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
                $('#das').on('change', unidad_administrativa);

                $('#provider').on('change', onselect_proveedor_llenar);

                $('#tipofactura').on('change', onselect_tipo);

                $('#partida').on('change', onselect_article);
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
                partida=$("#partida option:selected").text();
                montofactura=$("#montofactura").val();
                article_id=$("#article_id").val();
                presentacion=$("#presentacion").val();
                cantidad=$("#cantidad").val();
                precio=$("#precio").val();
                nombre_articulo=$("#article_id option:selected").text();


                if (partida != 'Seleccione una Partida..' && nombre_articulo != 'Seleccione un Articulo..' && cantidad != "" && precio != "") {
                    


                    var fila='<tr class="selected" id="fila'+cont+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td>'+partida+'</td>'
                            fila+='<td><input type="hidden" name="article_id[]"value="'+article_id+'">'+nombre_articulo+'</td>'
                            fila+='<td>'+presentacion+'</td>'
                            fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'
                            fila+='<td><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'
                            fila+='<td><input type="hidden" class="input_subtotal" name="totalbs[]" value="'+cantidad * precio+'">'+cantidad * precio+'</td>'
                        fila+='</tr>';
                    
                
                    // let monto_subtotal = parseFloat(calcular_total()+subtotal).toFixed(2);

                    // variables de detalle de factura y total de factura
                    let detalle_subtotal = parseFloat(calcular_total()+cantidad * precio ).toFixed(2);

                        let monto_factura = parseFloat($('#montofactura').val());
                    

                    if (detalle_subtotal <= monto_factura) {

                        cont++;
                        limpiar();
                        $('#detalles').append(fila);
                        $("#total").html("Bs. "+calcular_total().toFixed(2));

                    
                        //evaluar();
                        if (calcular_total().toFixed(2)==monto_factura.toFixed(2)) {
                            $('#btn_guardar').removeAttr('disabled');
                        }
                        else
                        {
                            $('#btn_guardar').attr('disabled', true);
                        }
                    }
                    else
                    {
                        // alert(total);
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
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
            }


            function eliminar(index)
            {
                total=total-subtotal[index];
                $("#total").html("Bs/." + total);
                $("#fila" + index).remove();
                $("#total").html("Bs. "+calcular_total().toFixed(2));
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


            
            function unidad_administrativa()
            {
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('ajax_unidad_administrativa')}}/'+id, function(data){
                        var html_unidad=    '<option value="">Seleccione Una Unidad Administrativa..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_unidad += '<option value="'+data[i].ID+'">'+data[i].Nombre+'</option>'

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
                    html_factura =  '<div class="form-group">'
                    html_factura+=       '<div class="form-line">'
                    html_factura+=            '<input type="number" name="nrocontrol" class="form-control" title="Introducir Nro Control Factura">'
                    html_factura+=       '</div>'
                    html_factura+=       '<small>Nro Control.</small>'
                    html_factura+=  '</div>'
                    $('#nrocontrol').html(html_factura);
                }
                else
                {
                    html_factura ='';
                    $('#nrocontrol').html(html_factura);
                }
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
@endif
