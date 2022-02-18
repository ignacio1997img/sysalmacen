<template>
    <main class="main">
            
            
                    
                    <div class="card-body">
                        <h5>Solicitud de Compra</h5>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select required name="sucursal_id" class="form-control form-control-sm select2bs4">
						                    
						                 </select>
		                            </div>
		                            <small>Sucursal (Usuario del Sistema).</small>
		                        </div>
		                    </div>
		                    <!-- === -->
		                    <div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select required name="entidad_id" class="form-control form-control-sm select2bs4">
						                    
						                 </select>
		                            </div>
		                            <small>Seleccionar Entidad Solicitante.</small>
		                        </div>
		                    </div>
		                    <!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" required id="numerosolicitud" name="numerosolicitud" placeholder="Introducir número de solicitud" autocomplete="off">
		                            </div>
		                            <small>Número Solicitud.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="date" class="form-control" id="fechaingreso" name="fechaingreso">
		                            </div>
		                            <small>Fecha Ingreso.</small>
		                        </div>
		                    </div>
							<!-- === -->
						</div>
                        <hr>
					    <h5>Proveedor + Detalle de Factura:</h5>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select required id="idproveedor" name="proveedor_id" class="form-control form-control-sm select2bs4">
		                                	<option selected value="">Seleccionar Proveedor</option>
						                   
						                 </select>
		                            </div>
		                            <small>Seleccionar Proveedor.</small>
		                        </div>
		                    </div>
		                    <!-- input axiliar -->
        					<input type="hidden" id="idproveedor_input" name="idproveedor_input" class="form-control">
		                    <!-- === -->
		                    <div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" id="nitproveedor" placeholder="NIT Proveedor" readonly>
		                            </div>
		                            <small>NIT.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" required id="numerofactura" name="numerofactura" placeholder="Introducir número factura" autocomplete="off">
		                            </div>
		                            <small>Número Factura.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="date" id="fechafactura" class="form-control" name="fechafactura">
		                            </div>
		                            <small>Fecha Factura.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="number" step="0.01" class="form-control form-control-sm" required id="montofactura" name="montofactura" placeholder="Introducir monto" autocomplete="off">
		                            </div>
		                            <small>Monto Factura.</small>
		                        </div>
		                    </div>
							<!-- === -->
						</div>
                        
		                <div class="form-group">
		                    <div class="form-line">
		                        <button type="button" id="bt_add" data-toggle="modal" data-target="#modalNuevo" class="btn btn-success"><i class="voyager-basket"></i> Agregar Artículo</button>
		                    </div>
		   	            </div>
		              
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Articulo</th>
                                    <th>Codigo Art.</th>
                                    <th>Categoría</th>
                                    <th>Presentación</th>
                                    <th>Precio Art.</th>
                                    <th>Cantidad</th>
                                    <th>SubTotal</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalEditar" title="Eliminar Articulos">
                                            <i class="voyager-trash"></i>
                                        </a>
                                    </td>
                                    <td>Equipos</td>
                                    <td>Dispositivos electrónicos</td>
                                    <td>
                                        <span class="badge badge-success">Activo</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
            <!--Inicio del modal agregar/actualizar-->
            <!-- Modal -->
            <div class="modal fade" id="modalNuevo" role="dialog">
                <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="t_planilla" class="form-control select2">
                                        
                                        </select>
                                    </div>
                                    <small>Seleccionar Artículo..</small>
                                </div>
		                        
                            </div>
                                    <!-- === -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-line">
                                            <input type="text" class="form-control form-control-sm" readonly id="codigoarticulo" placeholder="CÓDIGO.">
                                    </div>
                                    <small>Código Artículo.</small>
                                </div>
                            </div>
                                <!-- === -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control form-control-sm" readonly id="presentacion" placeholder="PRESENTACIÓN.">
                                    </div>
                                    <small>Presentación.</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" readonly id="nombrecategoria" placeholder="CATEGORÍA ARTÍCULO.">
		                            </div>
		                            <small>Categoria.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="number" class="form-control form-control-sm" id="precio" placeholder="Precio" autocomplete="off">
		                            </div>
		                            <small>Precio Artículo.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="number" class="form-control form-control-sm" id="cantidad" placeholder="Cantidad" autocomplete="off">
		                            </div>
		                            <small>Cantidad Artículo.</small>
		                        </div>
		                    </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal fade" id="modalNuevso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-success modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header  btn-success">
                            <h4 class="modal-title">Agregar Articulo</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row">
                                    <!-- === -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select required id="idarticulo_select2bs4" class="form-control form-control-sm select2bs4">
                                                    <option selected value="">Seleccionar Artículo/Producto</option>
                                                    
                                                </select>
                                            </div>
                                            <small>Seleccionar Artículo.</small>
                                        </div>
                                    </div>
                                    <!-- === -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control form-control-sm" readonly id="codigoarticulo" placeholder="CÓDIGO.">
                                            </div>
                                            <small>Código Artículo.</small>
                                        </div>
                                    </div>
                                    <!-- === -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control form-control-sm" readonly id="presentacion" placeholder="PRESENTACIÓN.">
                                            </div>
                                            <small>Presentación.</small>
                                        </div>
                                    </div>
                                    <!-- === -->
                                    
                                    <!-- === -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Categoría</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar la categoría?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        methods : {
            
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
