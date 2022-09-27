
<div class="col-md-12 text-right">

    <button type="button" onclick="report_print()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:5%">NRO&deg;</th>
                        <th style="width:15%">F. INGRESO</th>
                        <th style="width:25%">ENTIDAD + NRO COMPRA</th>
                        <th style="width:25%">PROVEEDOR</th>
                        <th style="width:25%">NRO</th>
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
                                <td>{{ $item->precio }}</td>
                                <td>{{ $item->cantrestante }}</td>
                                <td>{{ $item->totalbs }}</td>


                                                                                          
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