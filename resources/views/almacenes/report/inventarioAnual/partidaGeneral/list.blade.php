
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
                        <th style="text-align: center">DESCRIPCION</th>
                        <th style="text-align: center">CANTIDAD INICIAL</th>
                        <th style="text-align: center">SALDO INICIAL</th>
                        <th style="text-align: center">CANTIDAD FINAL</th>
                        <th style="text-align: center">SALDO FINAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        $cant1 =0;
                        $cant2 =0;
                        $total1 =0;
                        $total2 =0;
                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td>{{ $item->codigo }} - {{ $item->nombre }}</td>
                            <td style="text-align: right">{{ $item->cantidadinicial}}</td>
                            <td style="text-align: right">{{ $item->totalinicial}}</td>
                            <td style="text-align: right">{{ number_format($item->cantfinal,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->totalfinal, 2)}}</td>


                                                                                    
                        </tr>
                        @php
                            $count++;
                            $cant1 = $cant1 + $item->cantidadinicial;
                            $total1 = $total1 + $item->totalinicial;

                            $cant2 = $cant2 + $item->cantfinal;
                            $total2 = $total2 + $item->totalfinal;
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                    <tr style="text-align: center">
                        <td></td>
                        <td style="text-align: right">Total</td>
                        <td style="text-align: right">{{ $cant1}}</td>
                        <td style="text-align: right">{{ $total1}}</td>
                        <td style="text-align: right">{{ $cant2}}</td>
                        <td style="text-align: right">{{ $total2}}</td>


                                                                                
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