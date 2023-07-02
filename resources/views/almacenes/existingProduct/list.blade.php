
{{-- <div class="col-md-12 text-right">

    <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div> --}}
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataTableStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width: 1%;text-align: center">N&deg;</th>
                        <th style="width:25%; text-align: center">PARTIDA</th>
                        <th style="width:25%; text-align: center">ARTICULO</th>
                        <th style="width:25%; text-align: center">PRESENTACION</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse ($data as $item)
                            <tr >
                                <td style="text-align: center">{{ $count }}</td>
                                <td style="text-align: left">{{ $item->codigo }} - {{ $item->partida }}</td>
                                <td style="text-align: left">{{ $item->article_id }} - {{ $item->articulo }}</td>
                                <td style="text-align: left">{{ $item->presentacion }}</td>
                                                                                          
                            </tr>
                            @php
                                $count++;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="4">No se encontraron registros.</td>
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