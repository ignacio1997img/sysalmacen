<div style="margin-top: 20px">
    <table>
        <thead>
            <tr>
                <th rowspan="2"><strong>NRO</strong></th>
                <th rowspan="2"><strong>DESCRIPCION (ITEM)</strong></th>
                <th rowspan="2"><strong>U. DE MEDIDA</strong></th>
                <th rowspan="2"><strong>PRECIO UNITARIO</strong></th>
                <th colspan="4"><strong>CANTIDAD</strong></th>
                <th colspan="4"><strong>VALORES</strong></th>
            </tr>
            <tr>
                <th><strong>SALDO INICIAL</strong></th>
                <th><strong>ENTRADAS</strong></th>
                <th><strong>SALIDAS</strong></th>
                <th><strong>SALDO FINAL</strong></th>
                <th><strong>SALDO INICIAL</strong></th>
                <th><strong>ENTRADAS</strong></th>
                <th><strong>SALIDAS</strong></th>
                <th><strong>SALDO FINAL</strong></th>
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
                <tr>
                    <td>{{ $count }}</td>
                    <td>{{ $item->id }} - {{ $item->nombre }}</td>
                    <td>{{ $item->presentacion}}</td>
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
                    $cIni = $cIni + $item->cInicial;
                    $vIni = $cIni + $item->vInicial;

                    $cEnt = $cEnt + $item->cEntrada;
                    $vEnt = $vEnt + $item->vEntrada;

                    $cSal = $cSal + $item->cSalida;
                    $vSal = $vSal + $item->vSalida;

                    $cFin = $cFin + $item->cFinal;
                    $vFin = $vFin + $item->vFinal;
                    
                    
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="6">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="4" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($cIni,2)}}</th>
                <th style="text-align: right">{{number_format($cEnt,2)}}</th>
                <th style="text-align: right">{{number_format($cSal,2)}}</th>
                <th style="text-align: right">{{number_format($cFin,2)}}</th>

                <th style="text-align: right">{{number_format($vIni,2)}}</th>
                <th style="text-align: right">{{number_format($vEnt,2)}}</th>
                <th style="text-align: right">{{number_format($vSal,2)}}</th>
                <th style="text-align: right">{{number_format($vFin,2)}}</th>
            </tr>
        </tbody>
    
    </table>
</div>
