<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModalitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('modalities')->delete();
        
        \DB::table('modalities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'CONTRATACION MENOR',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:25:23',
                'updated_at' => '2022-03-02 14:25:23',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'APOYO NACIONAL A LA PRODUCCION Y EMPLEO',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:25:30',
                'updated_at' => '2022-03-02 14:25:30',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'LICITACION PUBLICA',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:25:39',
                'updated_at' => '2022-03-02 14:25:39',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'CONTRATACION POR EXCEPCION',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:25:46',
                'updated_at' => '2022-03-02 14:25:46',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'CONTRATACION POR DESASTRES Y/O EMERGENCIAS',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:25:54',
                'updated_at' => '2022-03-02 14:25:54',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'CONTRATACION DIRECTA DE BIENES Y SERVICIOS',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:26:01',
                'updated_at' => '2022-03-02 14:26:01',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'nombre' => 'CONTRATACIONES CON OBJETOS ESPECIFICOS',
                'condicion' => 1,
                'created_at' => '2022-03-02 14:26:07',
                'updated_at' => '2022-03-02 14:26:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}