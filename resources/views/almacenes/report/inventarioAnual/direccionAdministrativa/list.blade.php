
<div class="col-md-12 text-right">

    <button type="button" onclick="report_print()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:5px">NRO&deg;</th>
                        <th style="text-align: center">DA</th>
                        <th style="text-align: center">DIRECCIONES ADMINISTRATIVA</th>
                        <th style="text-align: center">SALDO INICIAL</th>
                        <th style="text-align: center">INGRESO</th>
                        <th style="text-align: center">SALIDA</th>
                        <th style="text-align: center">SALDO FINAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        $tingreso =0;
                        $tsalida =0;
                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td style="text-align: right">0.00</td>
                            <td style="text-align: right">{{ $item->ingreso}}</td>
                            <td style="text-align: right">{{ $item->salida}}</td>
                            <td style="text-align: right">{{ $item->salida}}</td>


                                                                                    
                        </tr>
                        @php
                            $count++;
                            $tingreso = $tingreso + $item->ingreso;
                            $tsalida = $tsalida + $item->salida;
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                    <tr style="text-align: center">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right">0.00</td>
                        <td style="text-align: right">{{ $tingreso}}</td>
                        <td style="text-align: right">{{ $tsalida}}</td>
                        <td style="text-align: right"></td>


                                                                                
                    </tr>
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