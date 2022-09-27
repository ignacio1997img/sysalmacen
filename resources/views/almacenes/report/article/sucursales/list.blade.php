
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
                        <th style="width:5%" rowspan="2">Nro&deg;</th>
                        <th rowspan="2" valign="">Descripción (Item)</th>
                        <th rowspan="2">Unidad de Medida</th>
                        <th rowspan="2">Precio Unitario</th>
                        <th style="text-align: center" colspan="4">Cantidad</th>
                        <th style="text-align: center" colspan="4">Valores</th>
                    </tr>
                    <tr>
                        <th>Saldo Inicial</th>
                        <th >Entradas</th>
                        <th >Salidas</th>
                        <th >Saldo Final</th>
                        <th>Saldo Inicial</th>
                        <th >Entradas</th>
                        <th >Salidas</th>
                        <th >Saldo Final</th>
                    </tr>
                </thead>
                <tbody>
                        
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