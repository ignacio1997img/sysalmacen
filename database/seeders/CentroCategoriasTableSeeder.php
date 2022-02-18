<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CentroCategoriasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('centro_categorias')->delete();
        
        \DB::table('centro_categorias')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'CENTROS DE ACOGIDAS TRINIDAD',
                'descripcion' => NULL,
                'condicion' => 1,
                'created_at' => '2022-02-15 22:25:49',
                'updated_at' => '2022-02-15 22:25:49',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'ASILOS DE ANCIANOS TRINIDAD',
                'descripcion' => NULL,
                'condicion' => 1,
                'created_at' => '2022-02-15 22:26:11',
                'updated_at' => '2022-02-15 22:26:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'ASILOS DE ANCIANOS PROVINCIAS:',
                'descripcion' => NULL,
                'condicion' => 1,
                'created_at' => '2022-02-15 22:26:57',
                'updated_at' => '2022-02-15 22:26:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}