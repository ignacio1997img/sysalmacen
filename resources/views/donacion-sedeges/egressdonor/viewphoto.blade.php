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


    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-title">
                    <i class="voyager-photo"></i> Fotos
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
                                        <div class="card-body">
                                            <h5>Centro de Establecimiento</h5>
                                            
                                            <table style="width:100%" class="table table-bordered table-striped table-sm">
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

        


        </script> 
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif