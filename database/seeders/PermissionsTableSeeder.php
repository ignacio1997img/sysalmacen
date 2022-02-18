<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        // \DB::table('permissions')->delete();
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');


        //Persimo Desarrollador
        Permission::generateFor('partidas');       
        Permission::generateFor('articles');   
        Permission::generateFor('providers');
        Permission::generateFor('modalities');
        Permission::generateFor('sucursals');

        Permission::generateFor('income');
        Permission::generateFor('egres');
        Permission::generateFor('bandeja');
        Permission::generateFor('solicitud');


        //---------- DONACIONES 
        Permission::generateFor('donacion_categorias');
        Permission::generateFor('donacion_articulos');
        Permission::generateFor('centro_categorias');


        // Permission::generateFor('solicitud_compras');
        // Permission::generateFor('facturas');
        // Permission::generateFor('detalle_facturas');
        // Permission::generateFor('archivos');
        // Permission::generateFor('sucursal_users');

        // Permission::generateFor('solicituds');
        // Permission::generateFor('solicitud_detalles');
        // Permission::generateFor('solicitud_derivadas');
        // Permission::generateFor('solicitud_rechazos');


        // Permission::generateFor('solicitud_egresos');//nooo
        // Permission::generateFor('detalle_egresos');//nooo
        // Permission::generateFor('inventarios');//nooo

        // Permission::generateFor('detalle_facturas');
        

        

        //grupos de direcciones administrativa

        
    }
}
