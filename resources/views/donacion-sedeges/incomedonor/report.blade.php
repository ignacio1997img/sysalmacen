<html mmoznomarginboxes="" mozdisallowselectionprint="">
    <head>
        <title>Comprobante Ingreso</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/print.style.css') }}" media="print">
        <style type="text/css">
            html
            {
                background-color: #FFFFFF; 
                margin: 0px;  /* this affects the margin on the html before sending to printer */
            }
            body {
                font-size: 14px !important;
            }
            table {
                font-size: 10px !important;
            }
            .centrar{
                width: 240mm;
                margin-left: auto;
                margin-right: auto;
                /*border: 1px solid #777;*/
                display: grid;
                padding: 10mm !important;
                -webkit-box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
                -moz-box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
                box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
            }
            /*For each sections*/
            .box-section {
                margin-top: 1mm;
                border: 1px solid #000;
                padding: 8px;
            }
            .alltables {
                width: 100%;
            }
            .alltables td{
                padding: 2px;
            }
            .box-margin {
                border: 1px solid #000;
                width: 120px;
            }
            .caja {
                border: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="noImprimir text-center">
            <button onclick="javascript:window.print()" class="btn btn-link">
                IMPRIMIR
            </button>
        </div>
        <div class="centrar">
            {{-- ENCABEZADO --}}
            <table class="alltables text-center">
                <tbody>
                    <tr>
                        <td><img src="{{ asset('images/lg.png') }}" width="100px"></td>
                        <td>
                            <table class="alltables">
                                <tr>
                                    <td  class="text-center">
                                        <h4 style="font-size: 22px;"><strong>GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI</strong></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="width: 40%">
                                        <span style="font-size: 20px;">
                                            <strong>UNIDAD DE ALMACENES DE DONACIONES SEDEGES</strong>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="width: 40%">
                                        <span style="font-size: 15px;">
                                            <strong>Acta de Ingreso de Donación <br>[{{$do->nrosolicitud}}/{{$do->gestion}}]</strong>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
        
                <table class="text-center" width="100%" style="font-size: 8pt">
                    <tr>
                        <th>LUGAR DONACION</th>
                        <th>FECHA DONACION</th>
                        <th>FECHA INGRESO</th>
                    </tr>
                    <tr>
                        <td>{{$centro->nombre}}</td>
                        <td>{{\Carbon\Carbon::parse($do->fechadonacion)->format('d/m/Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($do->fechaingreso)->format('d/m/Y')}}</td>
                    </tr>
                </table>
                <BR></BR>
                <table class="text-center" width="100%" style="font-size: 8pt">
                    <tr>
                        <th>TIPO</th>
                        <th>NIT / CI</th>
                        <th>NOMBRE</th>
                        <th>TEL</th>
                    </tr>
                    <tr>
                        <td>{{$donante->tipo}}</td>
                        <td>{{$donante->nit}}</td>
                        <td>{{$donante->name}}</td>
                        <td>
                            @if($donante->tel != null)
                                {{$donante->tel}}
                            @else
                                SN
                            @endif
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" style="font-size: 8pt">
                    <tr>
                        <th>OBSERVACION:</th>
                    </tr>
                    <tr>
                        <td>{{$do->observacion}}</td>
                    </tr>
                </table>
                <br>

           
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Categoria</th>
                        <th>Artículo</th>
                        <th>Presentacion</th>                        
                        <th>Estado</th>
                        <th>Característica</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Fecha Caducidad.</th>
                        <th>Total Parcial</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; $total =0;?>
                    @foreach($detalle as $data)       
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->categoria}}</td>
                        <td>{{$data->nombre}}</td>
                        <td>{{$data->presentacion}}</td>
                        <td>{{$data->estado}}</td>
                        <td>{{$data->caracteristica}}</td>
                        <td>{{$data->cantidad}}</td>
                        <td>{{$data->precio}}</td>
                        <td>
                            @if($data->caducidad == null)
                                SN
                            @else
                            {{\Carbon\Carbon::parse($data->caducidad)->format('d/m/Y')}}
                            @endif

                        </td>
                        <td>{{$data->precio * $data->cantidad}}</td>
                    </tr>   
                    <?php $i++; $total+= $data->cantidad * $data->precio;?>

                    @endforeach
                </tbody>    
            </table>
            <div class="row" style="font-size: 9pt">
                <p style="text-align: right">Total - Detalle de Compra: {{NumerosEnLetras::convertir($total,'Bolivianos',true)}}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="text-center col-6">
                        <br>
                        <br>
                        <br>
                        ________________________________________
                        <br>
                        <b>Responsable Sedeges</b>
                    </div>
                    <div class="text-center col-6">
                        <br>
                        <br>
                        <br>
                        ________________________________________
                        <br>
                        <b>{{$centro->nombre}}</b>
                    </div>
                </div>
            </div> 




            {{-- end section body --}}
            <!-- <div class="text-center">
                <p style="font-size: 13px;"><b>NOTA:</b> Este reporte muestra a detalle los productos egresados de fecha .</p>
            </div> -->
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td class="text-left" style="font-size: 10px;"></td>
                        <td class="text-right" style="font-size: 10px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>