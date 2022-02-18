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

@if(auth()->user()->hasPermission('add_bandeja'))

    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title">
                    <i class="voyager-edit"></i> Editar Solicitud Pedido
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
                                        {!! Form::open(['route' => 'store_editar_aprobar_solicitud', 'class' => 'was-validated'])!!}
                                        <input type="hidden" name="solicitud_id" value="{{$solicitud->id}}">
                                        <div class="card-body">
                                            <h5>Solicitud de Egreso</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h3 class="panel-title">Funcionario</h3>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{$derivacion[0]->de_nombre}}</p>
                                                    </div>
                                                    <hr style="margin:0;">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h3 class="panel-title">Unidad Administrativa</h3>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{$unidad[0]->Nombre}}</p>
                                                    </div>
                                                    <hr style="margin:0;">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h3 class="panel-title">Fecha Solicitud</h3>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{$solicitud->fechasolicitud}}</p>
                                                    </div>
                                                    <hr style="margin:0;">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h3 class="panel-title">Nro Proceso</h3>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{$solicitud->nroproceso}}</p>
                                                    </div>
                                                    <hr style="margin:0;">
                                                </div>
                                            </div>                                       
                                            <hr>
                                        
                                            <table id="detalles" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Opciones</th>
                                                        <th>Nro</th>
                                                        <!-- <th>id ocultar</th> -->
                                                        <th>Partida</th>                                                    
                                                        <th>Detalle</th>
                                                        <th>Unidad Medida</th>
                                                        <th>Cantidad Solicitada</th>
                                                        <th>Cantidad a Dar</th>                
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $cont = 0;
                                                        $total = 0;
                                                    @endphp
                                                    @foreach($detalle as $item)
                                                        @php
                                                            $cont++;
                                                        
                                                        @endphp
                                                        <tr class="selected" id="fila{{$cont}}">
                                                            <td>
                                                            
                                                                <button 
                                                                    type="button" 
                                                                    title="Eliminar articulo"
                                                                    class="btn btn-danger" 
                                                                    onclick="eliminar('{{$cont}}')";
                                                                >
                                                                <i class="voyager-trash"></i>
                                                                </button>
                                                                
                                                            </td>
                                                            <td>{{$cont}}</td>
                                                            <!-- <td><input type="hidden" name="detalle_id[]" value="{{$item->detalle_id}}">{{$item->detalle_id}}</td> -->
                                                            <td><input type="hidden" name="detalle_id[]" value="{{$item->detalle_id}}">{{$item->codigo}} - {{$item->partida}}</td>
                                                            <td>{{ $item->articulo}}</td>
                                                            <td>{{ $item->presentacion}}</td>
                                                            <td>{{ $item->cantidad }}</td>
                                                            <td><input type="text" class="form-control" name="cantidadentregar[]" value="{{ $item->cantidad }}"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                
                                                
                                            </table>
                                            
                                        </div>   
             
                                            <div class="card-footer">
                                                <button id="btn_guardar" type="submit"  class="btn btn-primary"><i class="voyager-save"></i> Guardar</button>
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
    </script>
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
