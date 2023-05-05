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
              {{-- action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif" --}}
              action="@if(!is_null($dataTypeContent->getKey())){{ route('update.users', $dataTypeContent->getKey()) }}@else{{ route('store.users') }}@endif"

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
                            @if(auth()->user()->hasRole('admin'))
                                <div class="form-group">
                                    <label class="control-label">INTERNO</label>
                                    <span class="voyager-question text-info pull-left" data-toggle="tooltip" data-placement="left" title=" Seleccione no si el funcionario es externo."></span>
                                    <input 
                                        type="checkbox" 
                                        name="tipo"
                                        id="toggleswitch" 
                                        data-toggle="toggle" 
                                        data-on="Sí" 
                                        data-off="No"
                                        checked 
                                        >
                                </div>

                                <div class="form-group">
                                    <label for="funcionario_id">Funcionario</label>
                                    <select 
                                        name="funcionario_id" 
                                        id="getfuncionario"
                                        class="form-control">
                                    </select>
                                </div>
                                <input type="hidden" id="people" name="name">

                            



                            {{-- <div class="form-group">
                                <label for="name">{{ __('voyager::generic.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('voyager::generic.name') }}"
                                       value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>                             --}}
                            
                            <div class="form-group">
                                <label for="email">{{ __('voyager::generic.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('voyager::generic.email') }}"
                                       value="{{ old('email', $dataTypeContent->email ?? '') }}">
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="password">{{ __('voyager::generic.password') }}</label>
                                @if(isset($dataTypeContent->password))
                                    <br>
                                    <small>{{ __('voyager::profile.password_hint') }}</small> {{-- Dejar vacío para mantener el mismo --}}
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
                                <div class="form-group" style="display:none">
                                    <label for="additional_roles">{{ __('voyager::profile.roles_additional') }}</label>
                                    @php
                                        $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('voyager::formfields.relationship')
                                </div>                                
                            @endcan
                            @php
                                $sucursal = null;
                            @endphp

                            @if ($dataTypeContent->id)
                                @php
                                    $sucursal = \App\Models\User::where('id', $dataTypeContent->id)->first();
                                @endphp                                
                            @endif

                            <div class="form-group">
                                <label for="sucursal_id">Almacen</label>
                                <select name="sucursal_id" id="sucursal_id" class="form-control select2">
                                    <option value="" >--Seleccione una opción--</option>
                                    @foreach(\App\Models\Sucursal::all() as $item)
                                        <option value="{{ $item->id }}" @if($sucursal) {{$sucursal->sucursal_id==$item->id?'selected':''}} @endif>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($dataTypeContent->id)
                                @php
                                    $direction = \App\Models\SucursalDireccion::with(['direction'])->where('sucursal_id', $dataTypeContent->sucursal_id)->where('status', 1)->where('deleted_at', null)->get();
                                    $unidad = \App\Models\Unit::where('direccion_id', $dataTypeContent->direccionAdministrativa_id)->where('estado', 1)->where('deleted_at', null)->get();
                                @endphp   
                                <div class="form-group">
                                    <label for="direction_id">Dirección Administrativa</label>
                                    <select name="direction_id" id="direction_id" class="form-control select2">                                   
                                        <option value="" >--Seleccione una opción--</option>
                                        @foreach ($direction as $item)
                                            <option value="{{$item->direction->id}}" {{$dataTypeContent->direccionAdministrativa_id==$item->direction->id?'selected':''}}>{{$item->direction->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>  
                                <div class="form-group">
                                    <label for="unit_id">Unidad Administrativa</label>
                                    <select name="unit_id" id="unit_id" class="form-control select2">   
                                        <option value="" >--Seleccione una opción--</option>
                                        @foreach ($unidad as $item)
                                            <option value="{{$item->id}}" {{$dataTypeContent->unidadAdministrativa_id==$item->id?'selected':''}}>{{$item->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- @php
                                    $contract = \App\Models\Contract::with(['unidad'])->where('person_id', $dataTypeContent->funcionario_id)->where('deleted_at', null)->where('status', 'firmado')->first();
                                    $unidad = [];
                                    if($contract)
                                    {
                                        $unidad = \App\Models\Unit::where('deleted_at', null)->where('direccion_id' , $contract->direccion_administrativa_id)->where('estado', 1)->get();
                                    }
                                @endphp    
                                <div class="form-group">
                                    <label for="unidad_administrativa_id">Unidad Administrativa</label>
                                    <select name="unidad_administrativa_id" class="form-control select2">
                                        @if ($dataTypeContent->id)
                                            @if ($contract->unidad_administrativa_id)
                                                <option value="{{ $contract->unidad_administrativa_id }}">{{ $contract->unidad->nombre }}</option>
                                            @else
                                                <option value="" >--Seleccione una opción--</option>
                                                @foreach($unidad as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            @endif                                        
                                        @else
                                            <option value="" >--Seleccione una opción--</option>
                                            @foreach($unidad as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                        
                                    </select>
                                </div>                             --}}
                            {{-- @else
                                @php
                                    $unidad = \App\Models\Unit::where('deleted_at', null)->where('estado', 1)->get()
                                @endphp --}}
                            @endif

                         
                            @php
                            if (isset($dataTypeContent->locale)) {
                                $selected_locale = $dataTypeContent->locale;
                            } else {
                                $selected_locale = config('app.locale', 'en');
                            }

                            @endphp
                            <div class="form-group" style="display:none">
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
            {{-- @if (auth()->user()->isAdmin()) --}}
            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
            {{-- @endif --}}
        </form>


            @if(!is_null($dataTypeContent->getKey()) && auth()->user()->isAdmin())
         

            <div class="row">
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

    {{-- <div class="modal modal-success fade" tabindex="-1" id="activar_modal" role="dialog">
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
    </div> --}}
    {{-- <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
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
    </div> --}}
@stop

@section('javascript')
<script src="{{ asset('js/select2.min.js')}}"></script>

    <script>


    var tipouser = 1;

    $('document').ready(function () {
        $('.toggleswitch').bootstrapToggle();
        $('#toggleswitch').on('change', function() {
            if (this.checked) {
                 tipouser = 1;
            } else {
                 tipouser = 0;
            }
        });

         ruta = "{{ route('user.getFuncionario') }}";
        $('#getfuncionario').select2({
            placeholder: '<i class="fa fa-search"></i> Buscar...',
            escapeMarkup : function(markup) {
                return markup;
            },
            language: {
                inputTooShort: function (data) {
                    return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                },
                noResults: function () {
                    return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                }
            },
            quietMillis: 250,
            minimumInputLength: 4,
            
            ajax: {
                url: ruta,
                type: "get",
                dataType: 'json',
                data:  (params) =>  {
                    var query = {
                        search: params.term,
                        type: tipouser
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            // templateResult: formatResultLandingPage,
            templateSelection: (opt) => opt.text
        });

        $('#getfuncionario').on('select2:select', function (e) {
           
            var data = e.params.data;
            if (data) {
                document.getElementById("people").value = data.text;
            }					
		});

        $('#sucursal_id').on('select2:select', function (e) {
           var data = e.params.data;
           if (data.id) {
                // alert(1)
               $.get('{{route('ajax-get.direccinsucursal')}}/'+data.id, function(data){
                // alert(data)
                        var html_direction=    '<option value="" selected>--Seleccione una opción--</option>'
                            for(var i=0; i<data.length; ++i)
                            html_direction += '<option value="'+data[i].direction.id+'">'+data[i].direction.nombre+'</option>'

                        $('#direction_id').html(html_direction);           
                });
           }	
           $('#direction_id').html(''); 				
           $('#unit_id').html(''); 				
       });
       $('#direction_id').on('select2:select', function (e) {
            var data = e.params.data;
            // alert(data.id)
            if (data.id) {
                $.get('{{route('ajax-get.unidadDirection')}}/'+data.id, function(data){
                    // alert(data)
                            var html_unit=    '<option value="" selected>--Seleccione una opción--</option>'
                                for(var i=0; i<data.length; ++i)
                                html_unit += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>'

                            $('#unit_id').html(html_unit);           
                    });
            }				
            $('#unit_id').html(''); 				
       });

    });






















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
