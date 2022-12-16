<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataStyle" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CI</th>
                    <th>Nombre completo</th>                    
                    <th>Cargo</th>
                    <th>Dirección Administrativa</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->people->ci }}</td>
                    <td>{{ $item->people->first_name}} {{ $item->people->last_name}}</td>
                    <td>{{ $item->cargo}}</td>
                    <td>{{ $item->direction->nombre}}</td>
                    <td>
                        <small>Inicio: {{$item->start}}</small><br>
                        <small>Fin: {{$item->finish}}</small>
                    </td>
                    <td>

                        @if ($item->status == 1)
                            <label class="label label-success">Activo</label>                            
                        @else
                            <label class="label label-danger">Inactivo</label>  
                        @endif
                    </td>


                    <td class="no-sort no-click bread-actions text-right">
                         
                       
                        @if($item->status == 1)
                            @if(auth()->user()->hasPermission('finish_people_ext'))
                                <a title="Aprobar prestamo" class="btn btn-sm btn-dark" onclick="finishItem('{{ route('people_ext.baja', ['people_ext' => $item->id]) }}')" data-toggle="modal" data-target="#modal_finish">
                                    <i class="fa-solid fa-thumbs-down"></i><span class="hidden-xs hidden-sm"> Baja</span>
                                </a>
                            @endif
                            @if(auth()->user()->hasPermission('delete_people_ext'))
                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('people_ext.destroy', ['people_ext' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                </button>
                            @endif
                        @endif
                    </td>
                
                    
                </tr>
                @empty
                    <tr style="text-align: center">
                        <td colspan="7" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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