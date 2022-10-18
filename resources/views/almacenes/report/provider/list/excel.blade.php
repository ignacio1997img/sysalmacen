<div style="margin-top: 20px">
    <table>
        <thead>
            
            <tr>
                <th><strong>NRO</strong></th>
                <th><strong>F. INGRESO</strong></th>
                <th><strong>ENTIDAD + NRO COMPRA</strong></th>
                <th><strong>PROVEEDOR</strong></th>
                <th><strong>NRO</strong></th>
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
                <td>{{date('d/m/Y', strtotime($item->fechaingreso))}}</td>
                <td>{{ $item->modalidad }} <br>{{$item->nrosolicitud}} </td>
                <td>{{ $item->proveedor }}</td>
                <td>{{ $item->tipofactura=='Orden_Compra'? 'Orden de Compra':'Nro Factura'}}<br>{{$item->nrofactura}}</td>
                <td>{{ $item->articulo }}</td>
                <td>{{ $item->presentacion }}</td>
                <td style="text-align: right">{{ number_format($item->precio,2) }}</td>
                <td style="text-align: right">{{ number_format($item->cantrestante,2) }}</td>
                <td style="text-align: right">{{ number_format($item->precio * $item->cantrestante,2) }}</td>                                                                                          
            </tr>
            @php
                $count++;
                $total = $total + ($item->cantrestante * $item->precio);
                $cant = $cant + $item->cantrestante;
            @endphp
        @empty
            <tr style="text-align: center">
                <td colspan="6">No se encontraron registros.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="8" class="text-right"><strong>TOTAL</strong></td>
            <td><strong>{{number_format($cant,2)}}</strong></td>
            <td><strong>{{number_format($total,2)}}</strong></td>
        </tr>
        </tbody>
    
    </table>
</div>
