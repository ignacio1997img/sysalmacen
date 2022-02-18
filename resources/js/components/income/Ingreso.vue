<template>
    <main class="main">        
                    <div class="card-body">
                        <h5>Solicitud de Compras</h5>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="sucursal_id" class="form-control select2">
                                            <option selected>Seleccione una Sucursal</option>
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Sucursal (Usuario del Sistema).</small>
		                        </div>
		                    </div>
		                    <!-- === -->
		                    <div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="entidad_id" class="form-control select2">
                                            <option selected>Seleccione una Entidad</option>
                                            <option v-for="entidad in arrayEntidad" :key="entidad.id" :value="entidad.id" v-text="entidad.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Seleccionar Entidad Solicitante.</small>
		                        </div>
		                    </div>
		                    <!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" id="numerosolicitud" placeholder="Introducir número de solicitud" autocomplete="off">
		                            </div>
		                            <small>Número Solicitud.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="date" class="form-control">
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
		                                <select v-model="proveedor_id"  @change="onChange" class="form-control">
		                                	<option selected :value="0">Seleccionar Proveedor</option>
                                            <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.razonsocial"></option>
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
		                                <input type="text" class="form-control form-control-sm" placeholder="NIT Proveedor" readonly>
		                            </div>
		                            <small>NIT.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Introducir número factura" autocomplete="off">
		                            </div>
		                            <small>Número Factura.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" v-model="fechafactura" class="form-control">
		                            </div>
		                            <small>Fecha Factura.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="number" step="0.01" class="form-control form-control-sm" id="montofactura" name="montofactura" placeholder="Introducir monto" autocomplete="off">
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
                    <h4 class="modal-title">Agregar Articulos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select v-model="sucursal_id" class="form-control select2">
                                            <option selected>Seleccione una Sucursal</option>
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
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
                                                <select class="form-control select2">
                                                    <option value="">Seleccione una Sucursal</option>
                                                    <!-- <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option> -->
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
        data()
        {
            return{
                sucursal_id : 0,
                entidad_id : 0,
                nrosolicitud : 0, 
                fechaingreso : 0,
                fechafactura : 0,
                nrofactura : 0,
                

                arraySucursal : [],
                arrayEntidad : [],

                
                arrayProveedor : [],
                proveedor_id : 0,
                nit : 0,
                arrayProvee : []
            }
            
        },
        methods : {
            selectSucursal()
            {   
                let me=this;    
                axios.get('/admin/incomes/selectsucursal')
                .then(function (response) {
                    var respuesta= response.data;
                    me.arraySucursal = respuesta.sucursal;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })                
            },
            selectEntidad()
            {   
                let me=this;    
                axios.get('/admin/incomes/selectentidad')
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayEntidad = respuesta.entidad;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })                
            },
            selectProveedor()
            {  
                let me=this;    
                axios.get('/admin/incomes/selectproveedor')
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayProveedor = respuesta.proveedor;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })                
            },

            onChange() {
                
                // console.log(event.target.value);
                let me=this;   
                // var id = event.target.value;

                var url = '/admin/incomes/selectproveedorsearch?page=' + this.proveedor_id;

                if(this.proveedor_id != 0)
                {   
                    alert(this.proveedor_id);
                    axios.get(url)
                    .then(function (response) {
                        
                        var respuesta= response.data;
                        me.arrayProvee = respuesta.proveedorfind;
                    
                
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    }) 
                }
                else
                {
                    this.arrayProvee =[];
                }

            }




            
        },
        mounted() {
            this.selectSucursal();
            this.selectEntidad();
            this.selectProveedor();
            // console.log('Component mounted.')
        }
    }

    $(function()
    {    
        // alert(5);
        // this.selectEntidad();

    });
    function onselect_barrio(ok)
    {
        
        
        
          
    }
</script>
