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
            @forelse ($collection as $item)
                <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $item['id'] }} - {{ $item['nombre'] }}</td>
                        <td style="text-align: right">{{ $item['presentacion']}}</td>
                        <td style="text-align: right">{{ $item['precio']}}</td>
                        <td style="text-align: right">{{ number_format($item['saldo'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['entrada'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['salida'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['final'],2,',', '.')}}</td>

                        <td style="text-align: right">{{ number_format($item['bssaldo'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['bsentrada'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['bssalida'],2,',', '.')}}</td>
                        <td style="text-align: right">{{ number_format($item['bsfinal'],2,',', '.')}}</td>
                                                                        
                </tr>
                @php
                    $count++;
                    $cIni = $cIni + $item['saldo'];
                    $vIni = $vIni + $item['bssaldo'];

                    $cEnt = $cEnt + $item['entrada'];
                    $vEnt = $vEnt + $item['bsentrada'];

                    $cSal = $cSal + $item['salida'];
                    $vSal = $vSal + $item['bssalida'];

                    $cFin = $cFin + $item['final'];
                    $vFin = $vFin + $item['bsfinal'];                            
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="12">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                    <th colspan="4" style="text-align: left">Total</th>
                    <th style="text-align: right">{{number_format($cIni,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($cEnt,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($cSal,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($cFin,2,',', '.')}}</th>
    
                    <th style="text-align: right">{{number_format($vIni,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($vEnt,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($vSal,2,',', '.')}}</th>
                    <th style="text-align: right">{{number_format($vFin,2,',', '.')}}</th>
            </tr>
        </tbody>
    
    </table>
</div>
