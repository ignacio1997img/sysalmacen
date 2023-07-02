<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permission_role')->delete();
        
        // Root
        $role = Role::where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions->pluck('id')->all());


    


         // Roles de Donacion en el Sedeges
      
         $role = Role::where('name', 'sedeges_admin')->firstOrFail();
         $permissions = Permission::whereRaw('table_name = "admin" or
                                            `key` = "browse_centro_categorias" or
                                            `key` = "read_centro_categorias" or
                                            `key` = "edit_centro_categorias" or
                                            `key` = "add_centro_categorias" or

                                            `key` = "browse_centros" or
                                            `key` = "read_centros" or
                                            `key` = "edit_centros" or
                                            `key` = "add_centros" or

                                            `key` = "browse_donacion_categorias" or
                                            `key` = "read_donacion_categorias" or
                                            `key` = "edit_donacion_categorias" or
                                            `key` = "add_donacion_categorias" or

                                            `key` = "browse_donacion_articulos" or
                                            `key` = "read_donacion_articulos" or
                                            `key` = "add_donacion_articulos" or
                                            `key` = "edit_donacion_articulos" or

                                            `key` = "browse_donador_personas" or
                                            `key` = "read_donador_personas" or
                                            `key` = "edit_donador_personas" or
                                            `key` = "add_donador_personas" or

                                            `key` = "browse_donador_empresas" or
                                            `key` = "read_donador_empresas" or
                                            `key` = "edit_donador_empresas" or
                                            `key` = "add_donador_empresas" or

                                            `key` = "browse_incomedonor" or
                                            `key` = "read_incomedonor" or
                                            `key` = "edit_incomedonor" or
                                            `key` = "add_incomedonor" or
                                            `key` = "browse_incomedonorstockview" or

                                            `key` = "browse_egressdonor" or
                                            `key` = "read_egressdonor" or
                                            `key` = "edit_egressdonor" or
                                            `key` = "add_egressdonor" or

                                            table_name = "view_stock_donacion" or
                                            `key` = "browse_clear-cache"')->get();
         $role->permissions()->sync($permissions->pluck('id')->all());


         $role = Role::where('name', 'sedeges_donacion_responsable')->firstOrFail();
         $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "incomedonor" or
                                             table_name = "egressdonor" or
                                             `key` = "browse_clear-cache"')->get();
         $role->permissions()->sync($permissions->pluck('id')->all());



        


         // ALAMACENES CENTRALES 


        //Para el administrador de todos los almacenes
        $role = Role::where('name', 'almacen_admin')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "outbox" or
                                            table_name = "inbox" or

                                            table_name = "existingproducts" or                                            
                                            

                                            `key` = "browse_partidas" or
                                            `key` = "read_partidas" or
                                            `key` = "edit_partidas" or
                                            `key` = "add_partidas" or

                                            `key` = "browse_articles" or
                                            `key` = "read_articles" or
                                            `key` = "edit_articles" or
                                            `key` = "add_articles" or

                                            `key` = "browse_sucursals" or

                                            `key` = "browse_inventory" or
                                            

                                            `key` = "browse_modalities" or
                                            `key` = "read_modalities" or
                                            `key` = "edit_modalities" or
                                            `key` = "add_modalities" or

                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or   
                                            
                                            `key` = "browse_printalmacen-partida-incomearticle" or    
                                            
                                            
                                            
                                            `key` = "browse_printalmacen-provider-list" or

                                            table_name = "inventory" or

                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //para los reportes
        $role = Role::where('name', 'almacen_subadmin')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or    
                                            
                                            
                                            `key` = "browse_printalmacen-partida-incomearticle" or    

                                            
                                            `key` = "browse_printalmacen-provider-list" or

                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //responsable del almacen: ingresa producto al almacen y dispensa los egresos o articulos
        $role = Role::where('name', 'almacen_responsable')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "income" or
                                            table_name = "egres" or


                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or  

                                            `key` = "browse_printalmacen-partida-incomearticle" or  
                                            
                                            `key` = "browse_printalmacen-provider-list" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //Para ingreso y egreso y reporte y aprobacion de solicitudes
        $role = Role::where('name', 'almacen_subadmin_responsable_aprobar')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "income" or
                                            table_name = "egres" or

                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "inbox" or

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or  

                                            `key` = "browse_printalmacen-partida-incomearticle" or  
                                            
                                            `key` = "browse_printalmacen-provider-list" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //Para ingreso y egreso y reporte y aprobacion de solicitudes y crear solicitud
        $role = Role::where('name', 'almacen_subadmin_responsable_aprobar_solicitar')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "income" or
                                            table_name = "egres" or

                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "inbox" or
                                            table_name = "outbox" or

                                            table_name = "existingproducts" or 

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or  

                                            `key` = "browse_printalmacen-partida-incomearticle" or  
                                            
                                            `key` = "browse_printalmacen-provider-list" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //Para ingreso y egreso y reporte y crear solicitud
        $role = Role::where('name', 'almacen_subadmin_responsable_solicitar')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "income" or
                                            table_name = "egres" or

                                            `key` = "browse_providers" or
                                            `key` = "read_providers" or
                                            `key` = "edit_providers" or
                                            `key` = "add_providers" or

                                            table_name = "outbox" or
                                            table_name = "existingproducts" or 

                                            table_name = "reports_anual" or
                                            
                                            `key` = "browse_printalmacen-article-list" or
                                            `key` = "browse_printalmacen-article-stock" or
                                            `key` = "browse_printalmacen-article-incomeoffice" or     
                                            `key` = "browse_printalmacen-article-egressoffice" or  

                                            `key` = "browse_printalmacen-partida-incomearticle" or  
                                            
                                            `key` = "browse_printalmacen-provider-list" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

    



        //para las solicitudes de pedidos
        $role = Role::where('name', 'almacen_solicitud_pedido')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "outbox" or
                                             table_name = "existingproducts" or 

                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        //Para aprobar las solicitudes de pedidos   
        $role = Role::where('name', 'almacen_solicitud_aprobar')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "inbox" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        //Para aprobar las solicitudes y hacer solicitudes 
        $role = Role::where('name', 'almacen_aprobar_solicitar')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "outbox" or
                                             table_name = "existingproducts" or 

                                             table_name = "inbox" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        //  // Roles de Donacion Sedeges 
        //  $role = Role::where('name', 'sedeges_donacion_almacen')->firstOrFail();
        //  $permissions = Permission::whereRaw('table_name = "admin" or
        //                                      table_name = "egressdonor"')->get();
        //  $role->permissions()->sync($permissions->pluck('id')->all());
    }
}