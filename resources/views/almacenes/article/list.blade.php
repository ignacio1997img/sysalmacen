<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="dataTable table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">Nro&deg;</th>
                    <th style="text-align: center">Nombre</th>
                    <th style="text-align: center">Presentación</th>
                    <th style="text-align: center">Partida</th>
                    @if (auth()->user()->hasRole('admin'))
                        <th style="text-align: center">Sucursal</th>
                    @endif
                    <th style="text-align: center">Estado</th>
                    <th style="text-align: right">Aciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td style="text-align: center">{{$item->nombre}}</td>
                        <td style="text-align: center">{{$item->presentacion}}</td>
                        <td style="text-align: center">{{$item->partida->nombre}}</td>
                        {{-- <td style="text-align: center">{{$item->direccion}}</td> --}}
                        @if (auth()->user()->hasRole('admin'))
                            <td style="text-align: center"><label class="label label-primary">{{$item->sucursal->nombre}}</label></td>                                                        
                        @endif
                        <td style="text-align: center">
                            @if ($item->condicion == 1)
                                <label class="label label-success">Activo</label>
                            @else
                                <label class="label label-danger">Inactivo</label>
                            @endif
                        </td>
                        <td style="text-align: right">
                            <div class="no-sort no-click bread-actions text-right">
                                @if(auth()->user()->hasPermission('read_articles'))
                                    <a href="{{route('voyager.articles.show',$item->id)}}" title="Ver" class="btn btn-sm btn-warning view">
                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                    </a>                                                          
                                @endif
                                @if(auth()->user()->hasPermission('edit_articles'))
                                    <a href="{{route('voyager.articles.edit',$item->id)}}" title="Editar" class="btn btn-sm btn-primary view">
                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                    </a>                                                          
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