
<div class="col-md-12 text-right">

    <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:5%">N&deg;</th>
                        <th style="width:15%; text-align: center">ALMACEN</th>
                        <th style="width:25%; text-align: center">USUARIOS</th>
                        <th style="width:10%; text-align: center">INGRESO <br>
                            CANT.</th>
                        <th style="width:10%; text-align: center">INGRESO <br>
                            BS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        



                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td>{{ $item->almacen }}</td>

                            @php
                                $funcionarios = Illuminate\Support\Facades\DB::connection('mamore')->table('people as p')
                                        ->join('sysalmacen.users as u', 'u.funcionario_id', 'p.id')
                                        ->join('sysalmacen.sucursal_users as su', 'su.user_id', 'u.id')

                                        ->where('su.condicion', 1)
                                        ->where('u.role_id', '!=', 1)
                                        ->where('su.sucursal_id', $item->sucursal_id)

                                        ->select('p.id', 'p.ci', 'p.first_name', 'p.last_name', 'su.sucursal_id')
                                        ->orderBy('p.last_name', 'asc')
                                        ->get();
                            @endphp


                            <td style="text-align: left">
                                @foreach ($funcionarios as $func)
                                    {{$func->ci}} - {{$func->first_name}} {{$func->last_name}} <br>
                                @endforeach
                            </td>
                            {{-- <td style="text-align: right">{{ $item->almacen}}</td> --}}
                            <td style="text-align: right">{{ number_format($item->cEntrada,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->vEntrada,2)}}</td>                                                                                    
                        </tr>
                        @php
                            $count++;
                            
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
                
            </table>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>