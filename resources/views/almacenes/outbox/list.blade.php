<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">Nro&deg;</th>
                    <th style="text-align: center">Oficina</th>
                    <th style="text-align: center">Nro Pedido</th>
                    <th class="text-align: center">Fecha Solicitud</th>

                    <th style="text-align: center">Fecha Registro</th>
                    @if(auth()->user()->hasRole(['admin']))
                        <th style="text-align: center">Sucursal</th>
                    @endif
                    <th class="text-align: center">Estado</th>
                    {{-- @if(auth()->user()->hasPermission('read_egres')||auth()->user()->hasPermission('edit_egres')||auth()->user()->hasPermission('delete_egres')) --}}
                    <th style="width: 150px">Accion</th>
                    {{-- @endif --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr style="text-align: center">
                        <td>{{$item->id}}</td>
                        <td style="text-align: center">
                            <p><small>Gestion: {{$item->gestion}}</small></p>
                            <p><small>{{$item->unidad_name}}</small></p>
                            <p><small>{{$item->direccion_name}}</small></p>
                        </td>
                        <td>
                            <p><small>{{$item->nropedido}}</small></p>
                        </td>

                        <td>{{date('d/m/Y', strtotime($item->fechasolicitud))}}</td>
                        <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>


                    
                        @if(auth()->user()->hasRole(['admin']))
                            <td style="text-align: center"><label class="label label-dark">{{$item->sucursal->nombre}}</label></td>
                        @endif
                        <td style="text-align: center">
                            @if ($item->status == 'Pendiente')
                                <label class="label label-warning">Pendiente</label>
                            @endif
                            @if ($item->status == 'Enviado')
                                <label class="label label-info">Enviado</label>
                            @endif
                            @if ($item->status == 'Aprobado')
                                <label class="label label-primary">Aprobado</label>
                            @endif
                            @if ($item->status == 'Rechazado')
                                <label class="label label-danger">Rechazado</label>
                            @endif
                            @if ($item->status == 'Entregado')
                                <label class="label label-success">Entregado</label>
                            @endif
                            @if ($item->status == 'pendienteeliminacion')
                                <label class="label label-warning">Pendiente en Eliminacion</label>
                            @endif
                            @if ($item->status == 'eliminado')
                                <label class="label label-danger">Eliminado</label>
                            @endif
                        </td>
                        <td style="text-align: right">
                            <div class="no-sort no-click bread-actions text-right">
                                @if ( $gestion && $item->status == 'Pendiente')
                                    <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalEnviar" title="Imprimir solicitud" class="btn btn-sm btn-success view">
                                        <i class="fa-solid fa-right-to-bracket"></i> Enviar
                                    </a>   
                                @endif


                                {{-- @if($item->status != 'Pendiente' && $item->status != 'Rechazado' && auth()->user()->hasPermission('read_egres')) --}}
                                @if( $item->status != 'Pendiente' && auth()->user()->hasPermission('print_outbox'))
                                    <a href="{{route('outbox.show',$item->id)}}" title="Imprimir solicitud" target="_blank" class="btn btn-sm btn-dark view">
                                        <i class="glyphicon glyphicon-print"></i>
                                    </a>   
                                @endif

                                @if ($item->status == 'pendienteeliminacion')
                                    <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalConfirmarEliminacion" title="Imprimir solicitud" class="btn btn-sm btn-success view">
                                        <i class="fa-solid fa-check"></i> Confirmar Eliminación
                                    </a>   
                                    <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalCancelarEliminacion" title="Eliminar" class="btn btn-sm btn-danger view">
                                        <i class="fa-solid fa-xmark"></i> Cancelar Eliminación
                                    </a>
                                @endif
                                
                                @if($gestion && $item->status == 'Pendiente' || $gestion && $item->status == 'Enviado')
                                    @if($item->gestion == $gestion->gestion)
                                        @if(auth()->user()->hasPermission('edit_outbox') && 1==2)
                                            <a href="{{route('egres.edit',$item->id)}}" title="Editar" class="btn btn-sm btn-info view">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('delete_outbox'))
                                            <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalEliminar" title="Eliminar" class="btn btn-sm btn-danger view">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                            </a>
                                        @endif
                                    @endif
                                @endif
                                
                            </div>
                        </td>                        
                    </tr>
                @empty
                    <tr>
                        @if(auth()->user()->hasRole(['admin']))
                            <td colspan="8">
                                <h5 class="text-center" style="margin-top: 50px">
                                    <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.5"> <br>
                                    No hay resultados
                                </h5>
                            </td>
                        @else
                            <td colspan="7">
                                <h5 class="text-center" style="margin-top: 50px">
                                    <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.5"> <br>
                                    No hay resultados
                                </h5>
                            </td>
                        @endif
                    </tr>
                @endforelse                                               
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>