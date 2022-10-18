<div style="margin-top: 20px">
    <table>
        <thead>
            
            <tr>
                <th><strong>NRO</strong></th>
                <th><strong>PARTIDA</strong></th>
                <th><strong>ARTICULO</strong></th>
                <th><strong>PRESENTACION</strong></th>
            </tr>
        </thead>
        <tbody>
            @php
                            $count = 1;
                        @endphp
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->codigo }} - {{ $item->partida }}</td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->presentacion }}</td>
                                                                                          
                            </tr>
                            @php
                                $count++;
                            @endphp
                        @empty
                            <tr style="text-align: center">
                                <td colspan="4">No se encontraron registros.</td>
                            </tr>
            @endforelse
          
      
        </tbody>
    
    </table>
</div>
