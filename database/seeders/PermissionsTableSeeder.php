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
        \DB::table('permissions')->delete();
        
        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);
        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');
        Permission::generateFor('permissions');


        Permission::generateFor('users');

        Permission::generateFor('settings');

        //---------- DONACIONES SEDEGES
        Permission::generateFor('centro_categorias');
        Permission::generateFor('centros');

        Permission::generateFor('donacion_categorias');
        Permission::generateFor('donacion_articulos');

        Permission::generateFor('donador_personas');
        Permission::generateFor('donador_empresas');

        Permission::generateFor('incomedonor');
        $keys = [
            'browse_incomedonorstockview',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'incomedonor',
            ]);
        }
        Permission::generateFor('egressdonor');

        $keys = [
            'browse_view_stock_donacion',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'view_stock_donacion',
            ]);
        }
        //FIN DONACIONES SEDEGES





        
 


        //Persimo Desarrollador
        Permission::generateFor('partidas');       
        Permission::generateFor('articles');   
        Permission::generateFor('providers');
        Permission::generateFor('modalities');
        Permission::generateFor('sucursals');


        Permission::generateFor('income');
        Permission::generateFor('egres');


        // Reports anual
        // print/almacen-inventarioAnual-da
        $keys = [
            // reportes anuales
            'browse_printalmacen-inventarioAnual-da',
            'browse_printalmacen-inventarioAnual-partida',
            'browse_printalmacen-inventarioAnual-detalle',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_anual',
            ]);
        }

        $keys = [
            // articulos
            'browse_printalmacen-article-list',
            'browse_printalmacen-article-stock',
            'browse_printalmacen-article-incomeoffice',
            'browse_printalmacen-article-egressoffice',

            // para las partidas
            'browse_printalmacen-partida-incomearticle',

            
            
            'browse_printalmacen-provider-list',

            // reportes adicionales
            'browse_printalmacen-user-list'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_almacen',
            ]);
        }


        $keys = [
            'browse_inventory',
            'start_inventory',
            'finish_inventory'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'inventory',
            ]);
        }



        $keys = [
            'browse_people_ext',
            'add_people_ext',
            'finish_people_ext',
            'delete_people_ext'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'people_ext',
            ]);
        }


        

        
    }
}
