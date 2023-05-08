@extends('voyager::master')

@section('page_title', 'Crear prestamos')

{{-- @if (auth()->user()->hasPermission('add_')) --}}
@if(auth()->user()->hasPermission('add_people_ext'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-person-digging"></i> Crear Persona Externas
        </h1>
        <a href="{{ route('people_ext.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('people_ext.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading">
                                <h5 id="h4" class="panel-title">Detalle Personas Externas</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">                                     
                                    <div class="form-group col-md-4">
                                        <small>Persona</small>
                                        <select name="people_id" id="people_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una persona --</option>
                                            @foreach ($people as $item)
                                                <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>       
                                    {{-- <div class="form-group col-md-4">
                                        <small>Direcion Administrativa</small>
                                        <select name="direccionAdministrativa_id" id="direccionAdministrativa_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una dirección --</option>
                                            @foreach ($direction as $item)
                                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>    --}}
                                    <div class="form-group col-md-8">
                                        <small>Cargo</small>
                                        <input type="text" name="cargo" class="form-control text">
                                    </div>                           
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Fecha Inicio</small>
                                        <input type="date" name="start" class="form-control text">
                                    </div> 
                                    <div class="form-group col-md-2">
                                        <small>Fecha Fin</small>
                                        <input type="date" name="finish" class="form-control text">
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                
            </form>              
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')

    @stop

@endif