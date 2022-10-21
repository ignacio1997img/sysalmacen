<div style="margin-top: 20px">
    <table>
        <thead>
            
            <tr>
                <th><strong>N&deg;</strong></th>
                <th><strong>F. EGRESO</strong></th>
                <th><strong>NRO PEDIDO</strong></th>
                <th><strong>OFICINA</strong></th>
                <th><strong>PARTIDA</strong></th>
                <th><strong>ARTICULO</strong></th>
                <th><strong>PRESENTACION</strong></th>
                <th><strong>PRECIO</strong></th>
                <th><strong>CANT.</strong></th>
                <th><strong>SUBTOTAL</strong></th>
            </tr>
        </thead>
        <tbody>
            @php
            $count = 1;
            $total = 0;
            $cant = 0;
        @endphp
        @forelse ($data as $item)
            <tr style="text-align: center">
                <td>{{ $count }}</td>
                <td>{{date('d/m/Y', strtotime($item->fechaegreso))}}</td>
                <td>{{ $item->nropedido}} </td>
                <td>{{ $item->unidad }}</td>
                <td>{{ $item->partida}}</td>
                <td>{{ $item->articulo }}</td>
                <td>{{ $item->presentacion }}</td>
                <td style="text-align: right">{{ number_format($item->precio,2) }}</td>
                <td style="text-align: right">{{ number_format($item->cantsolicitada,2) }}</td>
                <td style="text-align: right">{{ number_format($item->totalbs,2) }}</td>       
            </tr>
            @php
                $count++;
                $total = $total + $item->totalbs;
                $cant = $cant + $item->cantsolicitada;
            @endphp
        @empty
            <tr style="text-align: center">
                <td colspan="10">No se encontraron registros.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="8" class="text-right"><strong>TOTAL</strong></td>
            <td style="text-align: right"><strong>{{number_format($cant,2)}}</strong></td>
            <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
        </tr>
        </tbody>
    
    </table>
</div>
