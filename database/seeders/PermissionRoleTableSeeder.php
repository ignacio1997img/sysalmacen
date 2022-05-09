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
         $role = Role::where('name', 'sedeges_donacion_responsable')->firstOrFail();
         $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "incomedonor" or
                                             table_name = "egressdonor" or
                                             `key` = "browse_clear-cache"')->get();
         $role->permissions()->sync($permissions->pluck('id')->all());

         $role = Role::where('name', 'sedeges_donacion_view')->firstOrFail();
         $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "view_stock_donacion" or
                                             `key` = "browse_clear-cache"')->get();
         $role->permissions()->sync($permissions->pluck('id')->all());




         // ALAMACENES CENTRALES 
        $role = Role::where('name', 'almacen_responsable')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "income" or
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        //  // Roles de Donacion Sedeges 
        //  $role = Role::where('name', 'sedeges_donacion_almacen')->firstOrFail();
        //  $permissions = Permission::whereRaw('table_name = "admin" or
        //                                      table_name = "egressdonor"')->get();
        //  $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
