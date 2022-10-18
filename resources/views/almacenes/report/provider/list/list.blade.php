
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
                        <th>N&deg;</th>
                        <th>NIT</th>
                        <th>RAZON SOCIAL</th>
                        <th>RESPONSABLE</th>
                        <th>DIRECCIÓN</th>
                        <th>TELÉFONO</th>
                        <th>FAX</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse ($data as $item)
                            <tr style="text-align: center">
                                <td>{{ $count }}</td>
                                <td>{{ $item->nit }}</td>
                                <td>{{ $item->razonsocial }}</td>
                                <td>{{ $item->responsable }}</td>
                                <td>{{ $item->direccion }}</td>
                                <td>{{ $item->telefono }}</td>
                                <td>{{ $item->fax }}</td>
                                                                                          
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