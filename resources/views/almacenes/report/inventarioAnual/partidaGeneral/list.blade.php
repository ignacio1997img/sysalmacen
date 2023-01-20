@if (auth()->user()->hasRole('almacen_admin') || auth()->user()->hasRole('admin'))
    <div class="col-md-12 text-right">

        <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button>
        <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

    </div>
@endif

<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataTableStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:5px">N&deg;</th>
                        <th style="text-align: center">DESCRIPCION</th>
                        <th style="text-align: center">CANTIDAD INICIAL <br>01/01/{{$gestion}}</th>
                        <th style="text-align: center">SALDO INICIAL<br>01/01/{{$gestion}}<br>(Bs)</th>
                        <th style="text-align: center">CANTIDAD FINAL <br>31/12/{{$gestion}}</th>
                        <th style="text-align: center">SALDO FINAL <br>31/12/{{$gestion}}<br>(Bs)</th>
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
                            <td style="text-align: right">{{ number_format($item->cantidadinicial,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->totalinicial,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->cantfinal,2, ',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->totalfinal, 2, ',', '.')}}</td>


                                                                                    
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

                    <tr>
                        <th colspan="2" style="text-align: right">Total</th>
                        <th style="text-align: right">{{number_format($cant1,2, ',', '.')}}</th>
                        <th style="text-align: right">{{number_format($total1,2, ',', '.')}}</th>
                        <th style="text-align: right">{{number_format($cant2,2, ',', '.')}}</th>
                        <th style="text-align: right">{{number_format($total2,2, ',', '.')}}</th>
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