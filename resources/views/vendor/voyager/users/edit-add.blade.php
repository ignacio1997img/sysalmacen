@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
@stop

@section('content')
{{-- {{ dd($dataTypeContent->id) }} --}}
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                    {{-- <div class="panel"> --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name">{{ __('voyager::generic.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('voyager::generic.name') }}"
                                       value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>
                            
                            <?php 
                                // $funcionarios = Illuminate\Support\Facades\DB::connection('mysqlgobe')->table('contribuyente as co')                            
                                //             ->join('contratos as c', 'c.idContribuyente', 'co.N_Carnet')     
                                //             ->join('unidadadminstrativa as u', 'u.ID', 'c.idDependencia') 
                                //             ->join('cargo as ca', 'ca.ID', 'c.idCargo')
                                //             ->select('c.ID','c.idContribuyente', 'c.nombre AS nombrecontribuyente', 'ca.Descripcion as cargo', 'u.Nombre as unidad', 'c.Estado')
                                //             ->where('c.Estado', 1)
                                //             ->get();
                                            
                                $funcionarios = Illuminate\Support\Facades\DB::connection('mamore')->table('people as p')
                                            ->join('contracts as c', 'c.person_id', 'p.id')
                                            ->select('p.id', 'p.ci', 'p.first_name', 'p.last_name')
                                            ->where('c.status','firmado')
                                            ->orderBy('p.last_name', 'asc')
                                            ->get();
                                

                                $usuario = \App\Models\User::find($dataTypeContent->id);
                     
                                // dd($usuario);
                                // if($usuario == null)
                                // {
                                //     $usuario->merge(['funcionario_id' => 0]);
                                // }
                            
                                // dd($usuario);
                                // <input type="hidden" name="user_id" value="{{$dataTypeContent->id}}">

                            ?>
                            {{-- @if (auth()->user()->isAdmin()) --}}
                            <div class="form-group">
                                <label for="default_role">{{ __('Funcionario') }}</label>
                                <select name="funcionario_id" class="form-control select2">
                                    <option value="" selected>Seleccione</option>
                                    @foreach ($funcionarios as $data)    
                                        @if($usuario == null)                                
                                            <option value="{{$data->id}}" >{{$data->last_name}} {{$data->first_name}}</option>     
                                        @else
                                            <option value="{{$data->id}}" {{$data->id == $usuario->funcionario_id? 'selected' : '' }}>{{$data->last_name}} {{$data->first_name}}</option>    
                                        @endif                            
                                    @endforeach
                                </select>
                            </div>
                            {{-- @endif --}}
                            <div class="form-group">
                                <label for="email">{{ __('voyager::generic.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('voyager::generic.email') }}"
                                       value="{{ old('email', $dataTypeContent->email ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('voyager::generic.password') }}</label>
                                @if(isset($dataTypeContent->password))
                                    <br>
                                    <small>{{ __('voyager::profile.password_hint') }}</small>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                            </div>

                            @can('editRoles', $dataTypeContent)
                                <div class="form-group">
                                    <label for="default_role">{{ __('voyager::profile.role_default') }}</label>
                                    @php
                                        $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};

                                        $row     = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('voyager::formfields.relationship')
                                </div>
                                <div class="form-group">
                                    <label for="additional_roles">{{ __('voyager::profile.roles_additional') }}</label>
                                    @php
                                        $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('voyager::formfields.relationship')
                                </div>                                
                            @endcan
                            @php
                            if (isset($dataTypeContent->locale)) {
                                $selected_locale = $dataTypeContent->locale;
                            } else {
                                $selected_locale = config('app.locale', 'en');
                            }

                            @endphp
                            <div class="form-group">
                                <label for="locale">{{ __('voyager::generic.locale') }}</label>
                                <select class="form-control select2" id="locale" name="locale">
                                    @foreach (Voyager::getLocales() as $locale)
                                    <option value="{{ $locale }}"
                                    {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
        </form>


            @if(!is_null($dataTypeContent->getKey()))
            
            

            <div class="row">
                <div class="col-md-8">
                            {!! Form::open(['route' => 'usuario.store', 'class' => 'was-validated'])!!}
                                
                                <label for="default_role">{{ __('Almacen') }}</label>
                                <select name="branchoffice_id" class="form-control select2">
                                    <option value="" selected>Seleccione</option>
                                    @foreach (\App\Models\Sucursal::orderBy('nombre')->pluck('nombre','id') as $id => $warehouse)                                    
                                     <option value="{{ $id }}">{{ $warehouse }} </option>                                 
                                    @endforeach
                                </select>

                                <input type="hidden" name="user_id" value="{{$dataTypeContent->id}}">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit" data-toggle="tooltip"><i class="voyager-plus"></i></button>
                                </div>
                            {!! Form::close()!!} 
                                <br><br>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Alamacen</th>
                                        <th>Estado</th>    
                                        <th>Opcion</th>    
                                    </tr>
                                </thead>
                                <?php
                                    $ok = \App\Models\Sucursal::join('sucursal_users as u','u.sucursal_id','sucursals.id')
                                                        ->where('u.user_id',$dataTypeContent->id)
                                                        ->select('u.id','sucursals.nombre','u.condicion')
                                                        ->orderBy('nombre')->get();
                                    // dd($ok);
                                ?>
                                <tbody>
                                    @foreach($ok as $okk)
                                        <tr>
                                            <td>{{ $okk->id }}</td>
                                            <td>{{ $okk->nombre }}</td>
                                            <td>
                                                @if($okk->condicion === 1)
                                                    <span class="badge badge-primary">Activo</span>
                                                @else
                                                    <span class="badge badge-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            
                                            @if($okk->condicion == 1)
                                                <td class="text-center">
                                                    <a class="btn-lg btn-button" data-toggle="modal" data-target="#delete_modal" data-id="{{$okk->id}}" data-nombre="{{ $okk->nombre }}" title="Desactivar Almacen"><i class="voyager-trash" style="color: red;"></i></a>
                                                </td>
                                            @else
                                                <td class="text-center">
                                                    <a class="btn-lg btn-button" data-toggle="modal" data-target="#activar_modal" data-id="{{$okk->id}}" data-nombre="{{ $okk->nombre }}"title="Habilitar Almacen"><i class="voyager-check" style="color: rgb(0, 26, 255);"></i></a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                </div>
                <div class="col-md-4">

                </div>
            </div>
            @endif

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>

    <div class="modal modal-success fade" tabindex="-1" id="activar_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'almacen_activar',  'class' => 'was-validated']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-check"></i> Almacen</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="user_id" value="{{$dataTypeContent->id}}">
                    <div class="text-center" style="text-transform:uppercase">
                        <i class="voyager-check" style="color: rgb(35, 0, 236); font-size: 5em;"></i>
                        <br>
                        
                        <p><b>Desea Activar el Almacen?</b></p>
                        <b><p id="nombre"></p></b>
                    </div>
                </div>                
                <div class="modal-footer">
                    
                        <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, Activar">
                    
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
                {!! Form::close()!!} 
            </div>
        </div>
    </div>
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'almacen_desactivar',  'class' => 'was-validated']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Almacen</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="user_id" value="{{$dataTypeContent->id}}">
                    <div class="text-center" style="text-transform:uppercase">
                        <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                        <br>
                        
                        <p><b>Desea Desactivar el Almacen?</b></p>
                        <b><p id="nombre"></p></b>
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

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
        });


        $('#delete_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')
                var nombre = button.data('nombre')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                modal.find('.modal-body #nombre').text(nombre)
                
        });
        $('#activar_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')
                var nombre = button.data('nombre')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                modal.find('.modal-body #nombre').text(nombre)
                
        });
    </script>
@stop
