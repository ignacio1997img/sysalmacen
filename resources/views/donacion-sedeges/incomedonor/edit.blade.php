{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
@extends('voyager::master')

@section('page_title', 'Viendo Editar Ingresos')
@if(auth()->user()->hasPermission('edit_incomedonor'))
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
                    <i class="voyager-basket"></i> Editar Ingreso de Donaciones
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
                                        {!! Form::open(['route' => 'incomedonor_update', 'class' => 'was-validated', 'enctype' => 'multipart/form-data'])!!}
                                        <input type="hidden" name="id" id="id" value="{{$ingreso->id}}">
                                        <div class="card-body">
                                            <h5 id="subtitle">Centro de Establecimiento</h5>
                                            <div class="row">
                                                <!-- === -->
                                                <?php 
                                                    
                                                    $centro = \App\Models\Centro::find($ingreso->centro_id);
                                                 
                                                    $centroall = \App\Models\Centro::where('centrocategoria_id',$centro->centrocategoria_id)
                                                                                    ->where('condicion',1)
                                                                                    ->where('deleted_at',null)
                                                                                    ->get();
                                                ?>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="centrotipo" class="form-control select2" required>
                                                                <option value="">Seleccione una opcion</option>
                                                                @foreach($centrotipo as $data)
                                                                    <option value="{{$data->id}}" {{$data->id == $centro->centrocategoria_id ? 'selected' : ''}}>{{$data->nombre}}</option>
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
                                                                <option value="">Seleccione un centro..</option>
                                                                @foreach($centroall as $data)
                                                                    <option value="{{$data->id}}" {{$data->id == $ingreso->centro_id ? 'selected' : ''}}>{{$data->nombre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Centro.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date"  class="form-control text" name="fechadonacion" value="{{$ingreso->fechadonacion}}" required>
                                                        </div>
                                                        <small>Fecha de Donación.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date"  class="form-control text" name="fechaingreso" value="{{$ingreso->fechaingreso}}" required>
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
                                                            <textarea name="observacion" id="observacion" rows="2" class="form-control text">{{$ingreso->observacion}}</textarea>
                                                        </div>
                                                        <small>Observacion.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <table style="width:100%" id="detalle" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width:3%">Nro</th>
                                                        <th style="width:40%">Título</th>
                                                        <th style="width:40%">Fecha Registro</th>
                                                        <th style="width:17%"></th>                   
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                                    @forelse($archivos as $item)
                                                        <tr>
                                                            <td style="width:3%">{{$i}}</td>
                                                            <td style="width:40%">{{$item->nombre_origen}}</td>
                                                            <td style="width:40%">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
                                                            <td style="width:17%">
                                                                <a href="{{url('storage/'.$item->ruta)}}" title="Ver" target="_blank" class="btn btn-sm btn-success view">
                                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                                </a>
                                                                <a title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalEliminar">
                                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                                                </a>  
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>

                                                    @empty
                                                        <tr>
                                                            <td colspan="6"><h5 class="text-center">No hay archivos guardados</h5></td>
                                                        </tr>
                                                    @endforelse 
                                                </tbody>
                                                
                                            </table>
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
                                                            <select name="tipodonante" id="tipodonante"class="form-control" required>
                                                                <option value="">Seleccione un tipo..</option>
                                                                <option value="2" {{ 2 == $ingreso->tipodonante ? 'selected' : '' }}>Persona</option>
                                                                <option value="0" {{ 0 == $ingreso->tipodonante ? 'selected' : '' }}>Empresa</option>
                                                                <option value="1" {{ 1 == $ingreso->tipodonante ? 'selected' : '' }}>ONG</option>
                                                                <option value="3" {{ 3 == $ingreso->tipodonante ? 'selected' : '' }}>Anónimo</option>
                                                            </select>
                                                        </div>
                                                        <small>Tipos Donadores.</small>
                                                    </div>
                                                </div>
                                                                    <?php
                                                                        $codigo = 0;
                                                                        $ci =0;

                                                                        if($ingreso->tipodonante==1 || $ingreso->tipodonante==0)
                                                                        {
                                                                            $codigo = $ingreso->onuempresa_id;
                                                                            $ci = \App\Models\DonadorEmpresa::find($codigo);
                                                                            $ci = $ci->nit;
                                                                        }
                                                                        if($ingreso->tipodonante==2)
                                                                        {
                                                                            $codigo = $ingreso->persona_id;
                                                                            $ci = \App\Models\DonadorPersona::find($codigo);
                                                                            $ci = $ci->ci;
                                                                        }
                                                                        // dd($ci->nit);
                                                                    ?>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="donante" class="form-control select2" required>    
                                                                @if($anonimo==1)        
                                                                    <option value="0000">Anónimo</option>
                                                                @else
                                                                    <option value="">Seleccione un donante..</option>
                                                                    @foreach($donante as $item)
                                                                        <option value="{{$item->id}}_{{$item->ci}}" {{ $item->id == $codigo ? 'selected' : '' }}>{{$item->nombre}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar un Donante.</small>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" id="donante_id" name="donante_id" value="{{1 == $anonimo ? '' : $codigo}}">
                                            
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">                                                            
                                                            <input type="text" id="nit" class="form-control form-control-sm text" placeholder="Seleccione un donante" value="{{1 == $anonimo ? 'XXXXXXXX' : $ci}}" disabled readonly>
                                                        </div>
                                                        <small>CI./NIT.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <!-- <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="responsable" class="form-control form-control-sm" placeholder="Seleccione un Proveedor" disabled >
                                                        </div>
                                                        <small>Responsable.</small>
                                                    </div>
                                                </div> -->
                                            </div>
                                            
                                            <hr>
                                            <h5 id="subtitle">Categoria / Articulo:</h5>
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
                                                            <input type="text"disabled class="form-control form-control-sm text" id="presentacion" autocomplete="off">
                                                        </div>
                                                        <small>Presentacion.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="cantidad" min="1" class="form-control text" title="Cantidad">
                                                        </div>
                                                        <small>Cantidad Artículo.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <div class="form-line">
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
                                                        <th>Codigo</th>
                                                        <th>Categoria</th>
                                                        <th>Articulo</th>
                                                        <th>Presentación</th>
                                                        <th>Estado</th>
                                                        <th>Característica</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio Estimado.</th>
                                                        <th>Fecha Caducidad.</th>
                                                        <th>SubTotal</th>                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                      
                                                        $total = 0;
                                                    @endphp
                                                    @foreach($detalle as $item)
                                                        @php
                                                          
                                                            $total += $item->precio * $item->cantidad;
                                                        @endphp
                                                        <tr class="selected" id="fila{{$item->articulo_id}}">
                                                            <td>
                                                            
                                                                <button 
                                                                    type="button" 
                                                                    title="Eliminar articulo"
                                                                    class="btn btn-danger" 
                                                                    onclick="eliminar('{{$item->articulo_id}}')";
                                                                >
                                                                <i class="voyager-trash"></i>
                                                                </button>
                                                                
                                                            </td>
                                                            <?php
                                                                $caducidad ="";
                                                                if($item->caducidad =="")
                                                                {
                                                                    $caducidad='S/N';
                                                                }
                                                                else
                                                                {
                                                                    $caducidad = $item->caducidad;
                                                                }

                                                            ?>
                                                            <td>{{ $item->articulo_id}}</td>
                                                            <td>{{ $item->categoria}}</td>                                                            
                                                            <td><input type="hidden" class="input_article" name="articulo_id[]"value="{{ $item->articulo_id}}">{{ $item->nombre}}</td>
                                                            <td>{{ $item->presentacion}}</td>
                                                            <td><input type="hidden" name="estado[]" value="{{ $item->estado }}">{{ $item->estado }}</td>
                                                            <td><input type="hidden" name="caracteristica[]" value="{{ $item->caracteristica }}">{{ $item->caracteristica }}</td>
                                                            <td><input type="hidden" name="cantidad[]" value="{{ $item->cantidad}}">{{ $item->cantidad }}</td>
                                                            <td><input type="hidden" name="precio[]" value="{{ $item->precio }}">{{ $item->precio }}</td>
                                                            <td><input type="hidden" name="caducidad[]" value="{{ $item->caducidad}}">{{$caducidad}}</td>
                                                            <td><input type="hidden" class="input_subtotal" name="totalbs[]" value="{{ $item->precio * $item->cantidad }}">{{ $item->precio * $item->cantidad }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="10" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th><h4 id="total">Bs. {{ $total }}</h4></th>
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
        
            <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['route' => 'incomedonor_delete_file', 'method' => 'POST']) !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente archivo?</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="ingreso_id" value="{{$ingreso->id}}">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea eliminar el siguiente archivo?</b></p>
                            </div>
                        </div>                
                        <div class="modal-footer">
                            
                                <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                            
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                        </div>
                        {!! Form::close()!!} 
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

                $('#categoria').on('change', onselect_article);
                $('#articulo').on('change', onselect_presentacion);

                $('#centrotipo').on('change', onselect_centros);
                
                $('#tipodonante').on('change', onselect_donante);

                $('#donante').on('change', onselect_donante_llenar);


                $('#bt_add').click(function() {
                    agregar();
                });

            })

            // var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
            
                // alert(cont)
                categoria=$("#categoria option:selected").text();
                nombre_articulo=$("#articulo option:selected").text();

                estado=$("#estado option:selected").text();
                caracteristica=$("#caracteristica").val();

                articulo_id =$("#articulo").val();
                presentacion=$("#presentacion").val();
                precio=parseFloat($("#precio").val());
                cantidad=parseFloat($("#cantidad").val());
                caducidad=$("#caducidad").val();

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
     
                
                if (categoria != 'Seleccione una categoria..' && nombre_articulo != 'Seleccione un Articulo..' && auxprecio != '' && auxcantidad != '' && cantidad > 0 && precio >= 0)
                {                    
                    var fila='<tr class="selected" id="fila'+articulo_id+'">'
                        fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+articulo_id+')";><i class="voyager-trash"></i></button></td>'
                        fila+='<td>'+articulo_id+'</td>'
                        fila+='<td>'+categoria+'</td>'
                        fila+='<td><input type="hidden" class="input_article" name="articulo_id[]"value="'+articulo_id+'">'+nombre_articulo+'</td>' 
                        fila+='<td>'+presentacion+'</td>' 
                        fila+='<td><input type="hidden" name="estado[]" value="'+estado+'">'+estado+'</td>' 
                        fila+='<td><input type="hidden" name="caracteristica[]" value="'+caracteristica+'">'+caracteristica+'</td>' 
                        fila+='<td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'                       
                        fila+='<td><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'                        
                        fila+='<td><input type="hidden" name="caducidad[]" value="'+caducidad+'">'+auxcaducidad+'</td>'                        
                        fila+='<td><input type="hidden" class="input_subtotal" name="totalbs[]" value="'+cantidad * precio+'">'+cantidad * precio+'</td>'
                    fila+='</tr>';


                        

                        $(".input_article").each(function(){
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;
                        }); 
                        var ok=true

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


            function check(e,index) 
            {   
                alert(index)
                    tecla = (document.all) ? e.keyCode : e.which;

                    //Tecla de retroceso para borrar, siempre la permite
                    if (tecla == 8) {
                
                        return true;
                    }

                    var numero =0;
                    var letra =0;
                    // Patron de entrada, en este caso solo acepta numeros y letras
                    patron = /[0-9]/;
                    tecla_final = String.fromCharCode(tecla);
                    alert(tecla_final)
                                
                        
                                    
            }
            
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
                $("#caracteristica").val("");
            }


            function eliminar(index)
            {
                // total=total-subtotal[index];
                // alert(subtotal[index])
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
            function sumfila()
            {

            }






























            

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
                    $("#donante_id").val("");
                    if(id!=3)  
                    {
                        $.get('{{route('ajax_income_donante')}}/'+id, function(data){
                            var html_donante=    '<option value="">Seleccione un donante..</option>'
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
                        $("#donante_id").val('');

                    } 
                }
                else
                {
                    var html_donante=    ''       
                    $('#donante').html(html_donante);
                    $("#donante_id").val("");
                    $("#nit").val("");
                }
            }

            function onselect_donante_llenar()
            {
                donador = document.getElementById('donante').value.split('_');

                $("#donante_id").val(donador[0]);
                $("#nit").val(donador[1]);
            }


            $('#myModalEliminar').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget) //captura valor del data-empresa=""

                    var id = button.data('id')

                    var modal = $(this)
                    modal.find('.modal-body #id').val(id)
                    
            });



        </script> 
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif