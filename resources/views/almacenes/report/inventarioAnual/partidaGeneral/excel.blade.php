<div style="margin-top: 20px">
    <table>
        <thead>
            <tr>
                <th><strong>N&deg;</strong></th>
                <th><strong>DESCRIPCION</strong></th>
                <th><strong>CANTIDAD INICIAL 01/01/{{$gestion}}</strong></th>
                <th><strong>SALDO INICIAL 01/01/{{$gestion}} (Bs)</strong></th>
                <th><strong>CANTIDAD FINAL 31/12/{{$gestion}}</strong></th>
                <th><strong>SALDO FINAL 31/12/{{$gestion}} (Bs)</strong></th>
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
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ $item->codigo }} - {{ $item->nombre }}</td>
                            <td style="text-align: right">{{ number_format($item->cantidadinicial,2)}}</td>
                            <td style="text-align: right">{{ number_format($item->totalinicial,2)}}</td>
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
                        <tr>
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
            <tr>
                <th colspan="2" style="text-align: right">Total</th>
                <th style="text-align: right">{{number_format($cant1,2)}}</th>
                <th style="text-align: right">{{number_format($total1,2)}}</th>
                <th style="text-align: right">{{number_format($cant2,2)}}</th>
                <th style="text-align: right">{{number_format($total2,2)}}</th>
            </tr>
        </tbody>
    
    </table>
</div>
