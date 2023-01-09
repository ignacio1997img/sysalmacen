<div style="margin-top: 20px">
    <table>
        <thead>
            <tr>
                <th rowspan="2"><strong>N&deg;</strong></th>
                <th rowspan="2"><strong>DIRECCIONES ADMINISTRATIVA</strong></th>
                <th colspan="2"><strong>BS.</strong></th>
            </tr>
            <tr>
                <th><strong>SALDO INICIAL</strong></th>
                <th><strong>INGRESO</strong></th>
                <th><strong>SALIDAS</strong></th>
                {{-- <th><strong>SALDO FINAL</strong></th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                    $count = 1;
                        $si = 0;
                        $i = 0;
                        $s = 0;
                        $sf = 0;
                    @endphp
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $count }}</td>
                            <td style="text-align: left">{{ $item->nombre }}</td>
                            <td style="text-align: right">{{ number_format($item->inicio,2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->ingreso,2,',', '.')}}</td>
                            <td style="text-align: right">{{ number_format($item->salida,2,',', '.')}}</td>
                            @php
                                $aux =0;
                                if (($item->inicio + $item->ingreso) >= $item->salida)
                                {
                                    $aux = ($item->inicio + $item->ingreso) - $item->salida;
                                }
                                else
                                {
                                    $aux = $item->salida - ($item->inicio + $item->ingreso);
                                }   
                                $sf = $sf + $aux;                                                    
                            @endphp
                            {{-- <td style="text-align: right">{{ number_format($aux,2)}}</td>                             --}}
                                                                                    
                        </tr>
                        @php
                            $count++;
                            $si = $si + $item->inicio;
                            $i = $i + $item->ingreso;
                            $s = $s + $item->salida;
                            // $sf = 0;
                            
                        @endphp
                    @empty
                        <tr style="text-align: center">
                            <td colspan="5">No se encontraron registros.</td>
                        </tr>
                    @endforelse
            <tr>
                <th colspan="2" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($si,2,',', '.')}}</th>
                <th style="text-align: right">{{number_format($i,2,',', '.')}}</th>
                <th style="text-align: right">{{number_format($s,2,',', '.')}}</th>
                {{-- <th style="text-align: right">{{number_format($sf,2)}}</th> --}}
            </tr>
        </tbody>
    
    </table>
</div>
