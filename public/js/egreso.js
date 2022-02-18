var app = new Vue({
    el: '#egreso',
    data: {
      isProcessing: false,
      solicitudecompra: '',
      form: {},
      form1: {},
      producto: '',
      cantidad: 0,
      productos: [],
      unid_administrativas: [],
      items: [{
                articulo: '',
                codigoarticulo: 0,
                solicitudcompra_id: 0,
                facturadetalle_id: 0,
                preciocompra: 0,
                cantidad: 0,
                subtotal: 0
            }],
      errors: {}
    },
    created: function () {
      Vue.set(this.$data, 'form', _form);

    },
    mounted() {
     this.itemsarray();
     this.getunidades();
    },
    methods: {
       itemsarray(arrayDet){
           this.items.length = 0;
           this.form.egresodetalle.forEach((egreso)=>{
             this.items.push({
                    articulo: egreso.facturadetalle.articulo.nombre,
                    codigo: egreso.facturadetalle.articulo_id,
                    solicitudcompra_id: parseInt(egreso.solicitudcompra_id),
                    facturadetalle_id: egreso.facturadetalle.id,
                    preciocompra: egreso.facturadetalle.preciocompra,
                    cantidad: egreso.cantidad,
                    subtotal: (egreso.facturadetalle.preciocompra * egreso.cantidad)
             });

           // console.log(egreso.cantidad,egreso.facturadetalle.articulo_id)
           });
        },
      remove: function(detalle) {
        const index =this.items.indexOf(detalle);
        this.items.splice(index, 1);
      },
      agregarDetalle(){
        let me=this;
        if( me.producto=='' || me.cantidad==0 ){
         Swal.fire('Seleccione un producto!')
        }
        else{
           me.items.push({
                id: me.form.idtomo,
                codigo: me.form.codigobuscar,
                articulo: me.producto.nombre,
                codigo: me.producto.id,
                solicitudcompra_id : parseInt(me.solicitudecompra),
                facturadetalle_id: me.producto.idfacdet,
                preciocompra: me.producto.preciocompra,
                cantidad: me.cantidad,
                subtotal: me.producto.preciocompra * me.cantidad
            });
           // me.itemsarray( me.form1.tomos);
            me.cantidad=0;
        }
      },
      submit() {
          document.forms['prestamosform'].submit();
      },

      fetchProductos(){
        axios.get('/sisalmacen/egreso_facturadetalle?dep_id=' + this.solicitudecompra)
            .then(function (response) {
              app.productos = response.data;
              console.log(response.data);
            })
            .catch(function (error) {
              console.log(error);
            })
            .then(function () {
              // always executed
            });
      },
       update: function() {
         this.form.egresodetalle = this.items;
         this.isProcessing = true;
         axios.put('/sisalmacen/egreso/' + this.form.id, this.form)
             .then(function(response) {
              console.log(response);
               if(response.data.updated) {
                 window.location = '/sisalmacen/egreso';
               } else {
                   this.isProcessing = false;
               }
             })
             .catch(function(response) {
                 this.isProcessing = false;
                 Vue.set(this.$data, 'errors', response.data);
             })
      },
      getunidades(){
        axios.get('/sisalmacen/unidadadministrativa?dep_id='+this.form.direccionadministrativa_id)
             .then(res => {
              this.unid_administrativas = res.data;
             })
             .catch(res => {
               console.log(res);
             })
      }
    },
    computed: {
      Total: function() {
           return this.items.reduce(function(carry, product) {
              return carry + (parseFloat(product.cantidad) * parseFloat(product.preciocompra));
            }, 0);
      }
    }
  })