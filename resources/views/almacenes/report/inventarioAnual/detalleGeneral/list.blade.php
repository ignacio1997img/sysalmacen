
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
                        <th rowspan="2" style="width:5px">NRO&deg;</th>
                        <th rowspan="2" style="text-align: center">DESCRIPCION (ITEM)</th>
                        <th rowspan="2" style="text-align: center">U. DE MEDIDA</th>
                        <th rowspan="2" style="text-align: center">PRECIO UNITARIO</th>
                        <th colspan="4" style="text-align: center">CANTIDAD</th>
                        <th colspan="4" style="text-align: center">VALORES</th>
                        {{-- <th style="text-align: center">SALDO FINAL</th> --}}
                    </tr>
                    <tr>
                        {{-- <th style="width:5px">NRO&deg;</th> --}}
                        <th style="text-align: center">SALDO INICIAL</th>
                        <th style="text-align: center">ENTRADAS</th>
                        <th style="text-align: center">SALIDAS</th>
                        <th style="text-align: center">SALDO FINAL</th>
                        <th style="text-align: center">SALDO INICIAL</th>
                        <th style="text-align: center">ENTRADAS</th>
                        <th style="text-align: center">SALIDAS</th>
                        <th style="text-align: center">SALDO FINAL</th>
                        {{-- <th style="text-align: center">SALDO FINAL</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        $cIni = 0;
                        $vIni = 0;
                        $cEnt = 0;
                        $vEnt = 0;
                        $cSal = 0;
                        $vSal = 0;
                        $cFin = 0;
                        $vFin = 0;

                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td>{{ $item->id }} - {{ $item->nombre }}</td>
                            <td style="text-align: right">{{ $item->presentacion}}</td>
                            <td style="text-align: right">{{ $item->precio}}</td>
                            <td style="text-align: right">{{ number_format($item->cInicial,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->cEntrada,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->cSalida,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->cFinal,2)}}</td>

                            <td style="text-align: right">{{ number_format($item->vInicial,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->vEntrada,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->vSalida,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->vFinal,2)}}</td>
                                                                                    
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