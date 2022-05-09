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
        // Permission::generateFor('bandeja');
        // Permission::generateFor('solicitud');


        
        // // Planillas
        

        // Permission::generateFor('solicituddonor');
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
