<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:51:15',
                'route' => 'voyager.profile',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Users',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:08:02',
                'route' => 'voyager.users.index',
                'parameters' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:08:05',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2022-02-21 00:20:22',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:23',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Settings',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:25',
                'route' => 'voyager.settings.index',
                'parameters' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2021-06-02 14:07:53',
                'updated_at' => '2022-02-21 00:20:21',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'title' => 'Limpiar cache',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-refresh',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 7,
                'created_at' => '2021-06-25 18:03:59',
                'updated_at' => '2021-06-25 18:04:03',
                'route' => 'clear.cache',
                'parameters' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'title' => 'Partidas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-hammer',
                'color' => '#000000',
                'parent_id' => 15,
                'order' => 3,
                'created_at' => '2022-01-31 10:10:53',
                'updated_at' => '2022-02-08 10:59:29',
                'route' => 'voyager.partidas.index',
                'parameters' => 'null',
            ),
            13 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Opcion Almacen',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2022-01-31 10:23:22',
                'updated_at' => '2022-02-15 21:32:13',
                'route' => NULL,
                'parameters' => '',
            ),
            14 => 
            array (
                'id' => 21,
                'menu_id' => 1,
                'title' => 'Ingresos y Egresos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-receipt',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2022-01-31 12:24:59',
                'updated_at' => '2022-01-31 12:27:38',
                'route' => NULL,
                'parameters' => '',
            ),
            15 => 
            array (
                'id' => 22,
                'menu_id' => 1,
                'title' => 'Proveedores',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-milestone',
                'color' => '#000000',
                'parent_id' => 15,
                'order' => 2,
                'created_at' => '2022-01-31 12:48:27',
                'updated_at' => '2022-02-08 10:59:29',
                'route' => 'voyager.providers.index',
                'parameters' => 'null',
            ),
            16 => 
            array (
                'id' => 24,
                'menu_id' => 1,
                'title' => 'Ingresos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-plus',
                'color' => '#000000',
                'parent_id' => 21,
                'order' => 1,
                'created_at' => '2022-01-31 14:11:24',
                'updated_at' => '2022-02-04 00:26:54',
                'route' => 'income.index',
                'parameters' => 'null',
            ),
            17 => 
            array (
                'id' => 25,
                'menu_id' => 1,
                'title' => 'Egresos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-move',
                'color' => '#000000',
                'parent_id' => 21,
                'order' => 2,
                'created_at' => '2022-02-02 09:57:56',
                'updated_at' => '2022-02-02 09:58:19',
                'route' => 'egres.index',
                'parameters' => NULL,
            ),
            18 => 
            array (
                'id' => 26,
                'menu_id' => 1,
                'title' => 'Artículos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 4,
                'created_at' => '2022-02-03 16:13:34',
                'updated_at' => '2022-02-08 10:59:30',
                'route' => 'voyager.articles.index',
                'parameters' => NULL,
            ),
            19 => 
            array (
                'id' => 33,
                'menu_id' => 1,
                'title' => 'Modalidades de Compras',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-file-text',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 5,
                'created_at' => '2022-02-04 14:34:33',
                'updated_at' => '2022-02-15 20:52:17',
                'route' => 'voyager.modalities.index',
                'parameters' => NULL,
            ),
            20 => 
            array (
                'id' => 34,
                'menu_id' => 1,
                'title' => 'Sucursales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-shop',
                'color' => '#000000',
                'parent_id' => 15,
                'order' => 1,
                'created_at' => '2022-02-07 14:28:21',
                'updated_at' => '2022-02-08 10:59:29',
                'route' => 'voyager.sucursals.index',
                'parameters' => 'null',
            ),
            21 => 
            array (
                'id' => 35,
                'menu_id' => 1,
                'title' => 'Solicitudes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-news',
                'color' => '#000000',
                'parent_id' => 36,
                'order' => 2,
                'created_at' => '2022-02-08 10:59:05',
                'updated_at' => '2022-02-09 04:42:05',
                'route' => 'solicitud.index',
                'parameters' => 'null',
            ),
            22 => 
            array (
                'id' => 36,
                'menu_id' => 1,
                'title' => 'Solicitudes Egreso',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-news',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2022-02-09 04:36:56',
                'updated_at' => '2022-02-09 04:38:13',
                'route' => NULL,
                'parameters' => '',
            ),
            23 => 
            array (
                'id' => 37,
                'menu_id' => 1,
                'title' => 'Bandeja',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-paper-plane',
                'color' => '#000000',
                'parent_id' => 36,
                'order' => 1,
                'created_at' => '2022-02-09 04:41:30',
                'updated_at' => '2022-02-09 04:59:05',
                'route' => 'bandeja.index',
                'parameters' => 'null',
            ),
            24 => 
            array (
                'id' => 40,
                'menu_id' => 1,
                'title' => 'Categorias',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-categories',
                'color' => NULL,
                'parent_id' => 42,
                'order' => 5,
                'created_at' => '2022-02-15 21:07:50',
                'updated_at' => '2022-02-20 18:13:36',
                'route' => 'voyager.donacion-categorias.index',
                'parameters' => NULL,
            ),
            25 => 
            array (
                'id' => 41,
                'menu_id' => 1,
                'title' => 'Articulos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => '#000000',
                'parent_id' => 42,
                'order' => 6,
                'created_at' => '2022-02-15 21:14:50',
                'updated_at' => '2022-02-20 18:13:38',
                'route' => 'voyager.donacion-articulos.index',
                'parameters' => 'null',
            ),
            26 => 
            array (
                'id' => 42,
                'menu_id' => 1,
                'title' => 'Parametros Donaciones',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-double-down',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2022-02-15 22:19:23',
                'updated_at' => '2022-02-21 00:20:21',
                'route' => NULL,
                'parameters' => '',
            ),
            27 => 
            array (
                'id' => 43,
                'menu_id' => 1,
                'title' => 'Categorias',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-categories',
                'color' => '#000000',
                'parent_id' => 42,
                'order' => 1,
                'created_at' => '2022-02-15 22:23:09',
                'updated_at' => '2022-02-15 22:23:56',
                'route' => 'voyager.centro-categorias.index',
                'parameters' => 'null',
            ),
            28 => 
            array (
                'id' => 44,
                'menu_id' => 1,
                'title' => 'Centros de Establecimientos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-home',
                'color' => NULL,
                'parent_id' => 42,
                'order' => 2,
                'created_at' => '2022-02-15 22:49:51',
                'updated_at' => '2022-02-15 22:51:30',
                'route' => 'voyager.centros.index',
                'parameters' => NULL,
            ),
            29 => 
            array (
                'id' => 46,
                'menu_id' => 1,
                'title' => 'Personas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 42,
                'order' => 3,
                'created_at' => '2022-02-15 23:30:10',
                'updated_at' => '2022-02-20 18:12:23',
                'route' => 'voyager.donador-personas.index',
                'parameters' => NULL,
            ),
            30 => 
            array (
                'id' => 47,
                'menu_id' => 1,
                'title' => 'Empresas / ONG',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => NULL,
                'parent_id' => 42,
                'order' => 4,
                'created_at' => '2022-02-15 23:54:51',
                'updated_at' => '2022-02-20 18:12:25',
                'route' => 'voyager.donador-empresas.index',
                'parameters' => NULL,
            ),
            31 => 
            array (
                'id' => 48,
                'menu_id' => 1,
                'title' => 'Ingresos Y Egresos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-receipt',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2022-02-20 18:16:03',
                'updated_at' => '2022-02-20 18:19:45',
                'route' => NULL,
                'parameters' => '',
            ),
            32 => 
            array (
                'id' => 49,
                'menu_id' => 1,
                'title' => 'Ingreso',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-plus',
                'color' => '#000000',
                'parent_id' => 48,
                'order' => 1,
                'created_at' => '2022-02-20 18:16:54',
                'updated_at' => '2022-02-20 18:19:31',
                'route' => 'incomedonor.index',
                'parameters' => NULL,
            ),
            33 => 
            array (
                'id' => 50,
                'menu_id' => 1,
                'title' => 'Egreso',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-forward',
                'color' => '#000000',
                'parent_id' => 48,
                'order' => 2,
                'created_at' => '2022-02-20 18:18:43',
                'updated_at' => '2022-02-20 18:19:36',
                'route' => 'egressdonor.index',
                'parameters' => NULL,
            ),
            34 => 
            array (
                'id' => 51,
                'menu_id' => 1,
                'title' => 'Stock Vigente',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2022-02-21 00:20:06',
                'updated_at' => '2022-02-21 00:20:21',
                'route' => 'solicituddonor.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}