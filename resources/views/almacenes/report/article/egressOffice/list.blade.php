
<div class="col-md-12 text-right">

    <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataTableStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>                      
                    <tr>
                        <th style="width:5%">N&deg;</th>
                        <th style="width:15%">F. EGRESO</th>
                        <th style="width:25%">NRO PEDIDO</th>
                        <th style="width:25%">OFICINA</th>
                        <th style="width:25%">PARTIDA</th>
                        <th style="width:25%">ARTICULO</th>
                        <th style="width:25%">PRESENTACION</th>
                        <th style="width:25%">PRECIO</th>
                        <th style="width:25%">CANT.</th>
                        <th style="width:15%">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $count = 1;
                            $total = 0;
                            $cant = 0;
                        @endphp
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{date('d/m/Y', strtotime($item->fechaegreso))}}</td>
                                <td>{{ $item->nropedido}} </td>
                                <td>{{ $item->unidad }}</td>
                                <td>{{ $item->partida }}</td>
                                <td>{{ $item->articulo }}</td>
                                <td>{{ $item->presentacion }}</td>
                                <td style="text-align: right">{{ number_format($item->precio,2,',', '.')}}</td>
                                <td style="text-align: right">{{ number_format($item->cantsolicitada,2,',', '.')}}</td>
                                <td style="text-align: right">{{ number_format($item->totalbs,2,',', '.')}}</td>                        
                            </tr>
                            @php
                                $count++;
                                $total = $total + ($item->totalbs);
                                $cant = $cant + $item->cantsolicitada;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="10">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="8" class="text-right"><strong>TOTAL</strong></td>
                            <td><strong>{{number_format($cant,2,',', '.')}}</strong></td>
                            <td><strong>{{number_format($total,2,',', '.')}}</strong></td>
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