@extends('voyager::master')

@section('page_title', 'Error')

@section('content')
        <body>
                <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="text-center">
                        <h1 id="subtitle">403</h1>
                        <p class="fs-3"> <span class="text-danger">Aviso!</span> <small>Permiso denegado.</small></p>
                        <p class="lead">
                        {{-- En estos momentos el sistema se encuentra en mantenimiento, por favor intente más tarde. --}}
                        Ponte en contacto con el administrador del sistema<br> :)

                        </p>
                        <img src="{{asset('images/maintenance.gif')}}" width="250" height="200" border="0">
                        <br>

                        {{-- <a href="{{ url('/') }}"></a> --}}

                        {{-- <a href="https://wa.me/59167285914?text=Hola Ingeniero, tengo problema con mi usuario" target="_blank" title="contactar" >
                                <i class="fa-brands fa-whatsapp"></i> <span>Contactar al administrador</span>
                        </a> --}}
                </div>
                </div>
        </body>
@stop

@section('css')
        <style>
                #subtitle{
                        font-size: 60px;
                        color: rgb(12, 12, 12);
                        font-weight: bold;
                }

                /* span{
                        font-weight: bold;
                } */
                .lead{font-size: 32px;
                        color: rgb(12, 12, 12);
                        
                }
        </style>
@stop
