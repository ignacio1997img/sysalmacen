<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>

@extends('voyager::master')

@section('page_title', 'añadir Egresos')

@section('page_header')
    
    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-basket"></i> Añadir Egreso
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
                                {!! Form::open(['route' => 'egres.store', 'class' => 'was-validated'])!!}  
                                    <div class="card-body">
                                        <h5>Solicitud de Compras</h5>
                                        <div class="row">
                                            <!-- === -->
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select name="sucursal_id" class="form-control select2" required>
                                                            <option value="">Seleccione una sucursal</option>
                                                            @foreach ($sucursales as $sucursal)
                                                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small>Sucursal (Usuario del Sistema).</small>
                                                </div>
                                            </div>
                                            <!-- === -->
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select id="das" class="form-control select2" required>
                                                            <option value="">Seleccione una Direccion Administrativa</option>
                                                            @foreach ($da as $data)
                                                                <option value="{{$data->ID}}">{{$data->NOMBRE}}</option>
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
                                                        <select id="modalidad" class="form-control select2" >
                                                            
                                                        </select>
                                                    </div>
                                                    <small>Seleccionar Modalidad de Compra.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" name="nropedido" class="form-control form-control-sm" placeholder="Numero de Pedido" required>
                                                    </div>
                                                    <small>Numero de Pedido.</small>
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
                                                        <input type="date" name="fechaegreso" class="form-control" required>
                                                    </div>
                                                    <small>Fecha Egreso.</small>
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
                                                        <input type="text" id="precio" class="form-control form-control-sm" placeholder="Seleccione un Articulo" readonly>
                                                    </div>
                                                    <small>Precio.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" id="stok" class="form-control form-control-sm" placeholder="Seleccione un Articulo" readonly>
                                                    </div>
                                                    <small>Stock Artículo.</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" id="cantidad" step="0.01" class="form-control form-control-sm" placeholder="Introducir monto" autocomplete="off">
                                                    </div>
                                                    <small>Cantidad.</small>
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
                                                    <th>Precio Art.</th>
                                                    <th>SubTotal</th>
                
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <th colspan="7" style="text-align:right"><h5>TOTAL</h5></th>
                                                <th><h4 id="total">Bs. 0.00</h4></th>
                                            </tfoot>
                                            
                                        </table>
                                        <div class="card-footer">
                                            <button id="btn_guardar" type="submit"  class="btn btn-outline-info"><i class="fas fa-save"></i> Guardar</button>
                                        </div>  
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
        // $(document).ready(function() {
        //     // alert(5);
        // });
        


        $(function()
        {             
            $('#das').on('change', unidad_administrativa);

            $('#unidadEje').on('change', onselect_modalidad_compra);


            $('#modalidad').on('change', onselect_article);

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
                        fila+='<td>'+modalidad+'</td>'                         
                        fila+='<td>'+nombre_articulo+'</td>'
                        fila+='<td>'+presentacion+'</td>' 
                        fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'                       
                        fila+='<td><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'                        
                        fila+='<td><input type="hidden" class="input_subtotal" name="totalbs[]" value="'+cantidad * precio+'">'+cantidad * precio+'</td>'
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
		  	total=total-subtotal[index];
		  	$("#total").html("Bs/." + total);
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








        function unidad_administrativa()
        {
            $("#presentacion").val('');
                $("#stok").val('');
                $("#precio").val('');
                var html_modalidad=    ''       
                $('#modalidad').html(html_modalidad);
                var html_articulo=    ''       
                $('#article_id').html(html_articulo);

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
    
        function onselect_modalidad_compra()
        {
            // alert(4)
            $("#presentacion").val('');
                $("#stok").val('');
                $("#precio").val('');
                var html_articulo=    ''       
                $('#article_id').html(html_articulo);

            var id =  $(this).val();    
            if(id >=1)
            {
                $.get('{{route('ajax_solicitud_compra')}}/'+id, function(data){
                    var html_modalidad=    '<option value="">Seleccionar Modalidad Compra..</option>'
                        for(var i=0; i<data.length; ++i)
                        html_modalidad += '<option value="'+data[i].id+'">'+data[i].nombre+' - '+data[i].nrosolicitud+'</option>'

                    $('#modalidad').html(html_modalidad);;            
                });
            }
            else
            {
                var html_modalidad=    ''       
                $('#modalidad').html(html_modalidad);

   
            }
        }

        function onselect_article()
        {
            var id =  $(this).val();    
            $("#presentacion").val('');
                $("#stok").val('');
                $("#precio").val('');
            if(id >=1)
            {
                $.get('{{route('ajax_egres_select_article')}}/'+id, function(data){
                    var html_articulo=    '<option value="">Seleccione un Articulo..</option>'
                        for(var i=0; i<data.length; ++i)
                        html_articulo += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                    $('#article_id').html(html_articulo);;            
                });
            }
            else
            {
                var html_articulo=    ''       
                $('#article_id').html(html_articulo);
                   
            }
        }

        function onselect_presentacion()
        {
            var id =  $(this).val();    
            if(id >=1)
            {
                $.get('{{route('ajax_egres_select_article_detalle')}}/'+id, function(data){
                    $("#precio").val(data[0].precio);
                    $("#stok").val(data[0].cantrestante);
                    $("#presentacion").val(data[0].presentacion);
                                
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
