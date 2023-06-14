
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataTableStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th colspan="9">{{$partida->codigo}} {{$partida->nombre}}</th>
                    </tr>
                    <tr>
                        <th>N&deg;</th>
                        <th>N&deg; SOLICITUD</th>
                        <th>FECHA INGRESO </th>
                        <th>N&deg; FACTURA</th>
                        <th>ARTICULO</th>
                        <th>PRESENTACION</th>
                        <th>CANTIDAD</th>
                        <th>PRECIO U.</th>
                        <th>SUB TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $count = 1;
                            $total =0;
                        @endphp
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->nrosolicitud}} </td>
                                <td>{{date('d/m/Y', strtotime($item->fechaingreso))}}</td>
                                <td>{{ $item->nrofactura }}</td>
                                <td>{{ $item->articulo }}</td>
                                <td>{{ $item->presentacion }}</td>
                                <td style="text-align: right">{{ number_format($item->cantsolicitada,2,',', '.')  }}</td>
                                <td style="text-align: right">{{ number_format($item->precio,2,',', '.')  }}</td>
                                <td style="text-align: right"><small>{{ number_format($item->totalbs,2,',', '.') }}</small></td>                            
                            </tr>
                            @php
                                $count++;
                                $total = $total + $item->totalbs;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="9">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <th colspan="8" style="text-align: left">Total</th>
                            <th style="text-align: right"><small>Bs. {{ number_format($total,2,',', '.')}}</small></th>
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