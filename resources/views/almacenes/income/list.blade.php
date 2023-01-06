<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTableStyle" class="dataTable table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">Nro&deg;</th>
                    <th style="text-align: center">Entidad + Nro Compra</th>
                    <th style="text-align: center">Oficina</th>
                    <th style="text-align: center">Proveedor</th>
                    <th style="text-align: center">Factura/Orden Compra</th>
                    <th style="text-align: center">Fecha Registro</th>
                    <th style="text-align: center">Stock</th>
                    @if(auth()->user()->hasRole(['admin']))
                    <th style="text-align: center">Sucursal</th>
                    @endif
                    <th style="text-align: right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td style="text-align: center">{{$item->modality->nombre}} <br> {{$item->nrosolicitud}}</td>
                        <td style="text-align: center">
                            <p><small>Gestion: {{$item->gestion}}</small></p>
                            <p><small>{{$item->unidad->nombre}}</small></p>
                            <p><small>{{$item->direccion->nombre}}</small></p>
                        </td>
                        <td style="text-align: center">
                            <p><small>{{$item->factura[0]->proveedor->razonsocial}}</small></p>
                            <p><small>NIT: {{$item->factura[0]->proveedor->nit}}</small></p>
                        </td>
                        <td style="text-align: center">
                            <small>
                                <p>N:{{$item->factura[0]->nrofactura}}</p>
                                <p>Monto: Bs.{{$item->factura[0]->montofactura}}</p>
                                <p>Fecha: {{\Carbon\Carbon::parse($item->factura[0]->fechafactura)->format('d/m/Y')}}</p>
                            </small>
                        </td>
                        <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>
                        <td style="text-align: center">
                            @if ($item->stock == 1)
                                <label class="label label-success">SI</label>
                            @else
                                <label class="label label-danger">NO</label>
                            @endif
                        </td>
                        @if(auth()->user()->hasRole(['admin']))
                            <td style="text-align: center"><label class="label label-dark">{{$item->sucursal->nombre}}</label></td>
                        @endif
                        <td style="text-align: right">
                            <div class="no-sort no-click bread-actions text-right">
                                @if($item->condicion == 0)
                                    <a href="{{route('incomes-browse.salida',['income'=>$item->id])}}" title="Salida" class="btn btn-sm btn-dark">
                                        <i class="fa-solid fa-clipboard-list"></i> <span class="hidden-xs hidden-sm">Salidas</span>
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('read_income'))
                                    <a href="{{route('income_view_stock',$item->id)}}" title="Ver" target="_blank" class="btn btn-sm btn-info view">
                                        <i class="voyager-basket"></i> <span class="hidden-xs hidden-sm">Stock</span>
                                    </a>
                                    <a href="{{route('income_view',$item->id)}}" title="Ver" target="_blank" class="btn btn-sm btn-success view">
                                        <i class="glyphicon glyphicon-print"></i>
                                    </a>                                                                
                                @endif
                                
                                @if($gestion)
                                    @if($item->condicion == 1 && $item->inventarioAlmacen_id == $gestion->id)
                                        @if(auth()->user()->hasPermission('edit_incomes'))
                                            <a href="{{route('income.edit',$item->id)}}" title="Editar" class="btn btn-sm btn-warning">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('delete_income'))
                                            <button title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalEliminar">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                            </button>
                                        @endif
                                    @endif
                                @endif

                                
                            </div>
                        </td>                        
                    </tr>
                @endforeach                                          
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