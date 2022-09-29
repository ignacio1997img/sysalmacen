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

@section('page_header')
    
    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-basket"></i> Añadir Nuevo Inventario
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
                                        <h5>Solicitud de Compras</h5>
                                        <div class="row">
                                            <!-- === -->
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select name="branchoffice_id" class="form-control select2" required>
                                                            <option value="">Seleccione una sucursal</option>
                                                            @foreach ($sucursales as $sucursal)
                                                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small>Sucursal (Usuario del Sistema).</small>
                                                </div>
                                            </div>  
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" step="0.01" class="form-control form-control-sm" id="saldo_inicial" name="montofactura" placeholder="Introducir monto" autocomplete="off" required>
                                                    </div>
                                                    <small>Monto Inicial *(Bs).</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="descripcion" class="form-control" title="Descripcion" required>
                                                    </div>
                                                    <small>Descripcion.</small>
                                                </div>
                                            </div>     
                                        </div>       
                                    </div>   
                                    <div class="card-footer">
                                        <button id="btn_guardar" disabled type="submit"  class="btn btn-outline-info"><i class="fas fa-save"></i> Guardar</button>
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
    
    <div class="modal fade modal-success" role="dialog" id="modal-create">
        <div class="modal-dialog modal-md">
            <div class="modal-content">                
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-plus"></i>Registrar</h4>
                </div>
                {!! Form::open(['route' => 'people-perfil-experience.store','class' => 'was-validated'])!!}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Tipo de Trabajo:</b></span>
                                </div>
                                <select name="rubro_id" id="rubro_id" class="form-control select2" required>
                                    <option value="">Seleccione un tipo..</option>
                                    @foreach($rubro as $item)
                                        @if ($item->id != 4 && auth()->user()->hasRole('trabajador'))
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12" id="div_model">
                            </div>
                        </div>     

                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-between">
                        <button type="button text-left" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Volver">Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" title="Registrar..">
                            Registrar
                        </button>
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
  
@stop
