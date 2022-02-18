<template>
    <main class="main">        
                    <div class="card-body">
                        <h5>Solicitud de Compras</h5>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-6">
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
		                    <div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="entidad_id" class="form-control form-control-sm select2">
                                            <option selected>Seleccionar Dirección Administrativa</option>
						                    
						                 </select>
		                            </div>
		                            <small>Dirección Administrativa.</small>
		                        </div>
		                    </div>
						</div>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="sucursal_id" class="form-control select2">
                                            <!-- <option selected>Seleccione una </option> -->
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Unidad Administrativa.</small>
		                        </div>
		                    </div>
		                    <!-- === -->
		                    <div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Código de Pedido.</small>
		                        </div>
		                    </div>
						</div>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="date" v-model="fechafactura" class="form-control">
		                            </div>
		                            <small>Fecha Solicitud.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="date" v-model="fechafactura" class="form-control">
		                            </div>
		                            <small>Fecha Egreso.</small>
		                        </div>
		                    </div>
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="sucursal_id" class="form-control select2">
                                            <option selected>Seleccione Una Cuenta </option>
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Cuenta.</small>
		                        </div>
		                    </div>
							<!-- === -->
						</div>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="sucursal_id" class="form-control select2">
                                            <!-- <option selected>Seleccione una </option> -->
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Solicitud de Compra.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-6">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <select v-model="sucursal_id" class="form-control select2">
                                            <!-- <option selected>Seleccione una </option> -->
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.nombre"></option>
                                        </select>
		                            </div>
		                            <small>Artículo/Productos.</small>
		                        </div>
		                    </div>
						</div>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-3">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Código de Articulo.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-5">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Categoría.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-4">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Presentación.</small>
		                        </div>
		                    </div>
						</div>
                        <div class="row">
							<!-- === -->
							<div class="col-sm-4">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Precio.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-4">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control form-control-sm" placeholder="Código de Pedido" readonly>
		                            </div>
		                            <small>Stock Artículo.</small>
		                        </div>
		                    </div>
                            <div class="col-sm-4">
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="number" step="0.01" class="form-control form-control-sm" required id="montofactura" name="montofactura" placeholder="Introducir monto" autocomplete="off">
		                            </div>
		                            <small>Cantidad.</small>
		                        </div>
		                    </div>
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
                                    <th>solicitud Compra</th>
                                    <th>Factura Detalle</th>
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
                proveedor_id : 0,
                arraySucursal : [],
                arrayProveedor : [],

                total : 0
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
            }
        },
        mounted() {
            this.selectSucursal();
            this.selectProveedor();
            // console.log('Component mounted.')
        }
    }
</script>
