<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DonacionCategoriasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('donacion_categorias')->delete();
        
        \DB::table('donacion_categorias')->insert(array (
            0 => 
            array (
                'id' => 8,
                'nombre' => 'IMPLEMENTOS MOTRICES',
                'descripcion' => 'IMPLEMENTOS MOTRICES',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:10:47',
                'updated_at' => '2022-02-16 13:10:47',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 9,
                'nombre' => 'VESTIMENTA',
                'descripcion' => 'VESTIMENTA',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:19:32',
                'updated_at' => '2022-02-16 13:19:32',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 10,
                'nombre' => 'UTENCILIOS',
                'descripcion' => 'UTENCILIOS',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:22:47',
                'updated_at' => '2022-02-16 13:22:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 11,
                'nombre' => 'MATERIAL DE LIMPIEZA Y PERSONAL',
                'descripcion' => 'MATERIAL DE LIMPIEZA Y PERSONAL',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:23:05',
                'updated_at' => '2022-02-16 13:23:05',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 12,
                'nombre' => 'COMESTIBLES',
                'descripcion' => 'COMESTIBLES',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:23:25',
                'updated_at' => '2022-02-16 13:23:25',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 13,
                'nombre' => 'ALIMENTOS FRESCOS',
                'descripcion' => 'ALIMENTOS FRESCOS',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:23:39',
                'updated_at' => '2022-02-16 13:23:39',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 14,
                'nombre' => 'VERDURAS',
                'descripcion' => 'VERDURAS',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:23:57',
                'updated_at' => '2022-02-16 13:23:57',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 15,
                'nombre' => 'TUBERCULOS',
                'descripcion' => 'TUBERCULOS',
                'condicion' => 1,
                'created_at' => '2022-02-16 13:27:17',
                'updated_at' => '2022-02-16 13:27:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 16,
                'nombre' => 'MEDICAMENTOS',
                'descripcion' => 'MEDICAMENTOS',
                'condicion' => 1,
                'created_at' => '2022-02-16 14:17:32',
                'updated_at' => '2022-02-16 14:17:32',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 17,
                'nombre' => 'MATERIAL DE BIOSEGURIDAD',
                'descripcion' => 'MATERIAL DE BIOSEGURIDAD',
                'condicion' => 1,
                'created_at' => '2022-02-16 14:17:45',
                'updated_at' => '2022-02-16 14:17:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}