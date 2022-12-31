
<div class="col-md-12 text-right">

    <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:5px">N&deg;</th>
                        <th rowspan="2" style="text-align: center">DIRECCIONES ADMINISTRATIVA</th>
                        <th colspan="4" style="text-align: center">BS.</th>
                    </tr>
                    <tr>
                        {{-- <th style="width:5px">NRO&deg;</th> --}}
                        <th style="text-align: center">SALDO INICIAL</th>
                        <th style="text-align: center">INGRESO</th>
                        <th style="text-align: center">SALIDAS</th>
                        {{-- <th style="text-align: center">SALDO FINAL</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse ($direction as $item)
                        <tr>
                            <td>{{ $count }}</td>
                            <td style="text-align: left">{{ $item->nombre }}</td>
                            <td style="text-align: right">{{ number_format($item->inicio,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->ingreso,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->salida,2)}}</td>                                                                                    
                        </tr>
                        @php
                            $count++;                            
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="5">No se encontraron registros.</td>
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