<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartidasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partidas')->delete();
        
        \DB::table('partidas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'codigo' => '3.1.1.10',
                'nombre' => 'GASTOS POR REFRIGERIOS AL PERSONAL PERMANENTE, EVENTUAL Y CONSULTORES INDIVIDUALES DE LÍNEA DE LAS INSTITUCIONES PÚBLICAS',
                'created_at' => '2022-03-02 14:36:10',
                'updated_at' => '2022-03-02 14:36:10',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'codigo' => '3.1.1.20',
                'nombre' => 'GASTOS POR ALIMENTACIÓN Y OTROS SIMILARES',
                'created_at' => '2022-03-02 14:36:27',
                'updated_at' => '2022-03-02 14:36:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'codigo' => '3.1.1.40',
                'nombre' => 'ALIMENTACIÓN HOSPITALARIA, PENITENCIARIA, AERONAVES Y OTRAS ESPECÍFICAS',
                'created_at' => '2022-03-02 14:36:38',
                'updated_at' => '2022-03-02 14:36:38',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'codigo' => '3.1.2',
                'nombre' => 'ALIMENTOS PARA ANIMALES',
                'created_at' => '2022-03-02 14:36:54',
                'updated_at' => '2022-03-02 14:36:54',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'codigo' => '3.1.3',
                'nombre' => 'PRODUCTOS AGRÍCOLAS, PECUARIOS Y FORESTALES',
                'created_at' => '2022-03-02 14:38:31',
                'updated_at' => '2022-03-02 14:38:31',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'codigo' => '3.2.1',
                'nombre' => 'PAPEL',
                'created_at' => '2022-03-02 14:38:41',
                'updated_at' => '2022-03-02 14:38:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'codigo' => '3.2.2',
                'nombre' => 'PRODUCTOS DE ARTES GRÁFICAS',
                'created_at' => '2022-03-02 14:38:53',
                'updated_at' => '2022-03-02 14:38:53',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'codigo' => '3.2.3',
                'nombre' => 'LIBROS, MANUALES Y REVISTAS',
                'created_at' => '2022-03-02 14:39:05',
                'updated_at' => '2022-03-02 14:39:05',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'codigo' => '3.2.5',
                'nombre' => 'PERIÓDICOS Y BOLETINES',
                'created_at' => '2022-03-02 14:39:23',
                'updated_at' => '2022-03-02 14:39:23',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'codigo' => '3.3.1',
                'nombre' => 'HILADOS, TELAS, FIBRAS Y ALGODÓN',
                'created_at' => '2022-03-02 14:39:32',
                'updated_at' => '2022-03-02 14:39:32',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'codigo' => '3.3.2',
                'nombre' => 'CONFECCIONES TEXTILES',
                'created_at' => '2022-03-02 14:39:44',
                'updated_at' => '2022-03-02 14:39:44',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'codigo' => '3.3.3',
                'nombre' => 'PRENDAS DE VESTIR',
                'created_at' => '2022-03-02 14:39:55',
                'updated_at' => '2022-03-02 14:39:55',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'codigo' => '3.3.4',
                'nombre' => 'CALZADOS',
                'created_at' => '2022-03-02 14:40:06',
                'updated_at' => '2022-03-02 14:40:06',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'codigo' => '3.4.1.10',
                'nombre' => 'COMBUSTIBLES, LUBRICANTES Y DERIVADOS PARA CONSUMO',
                'created_at' => '2022-03-02 14:40:18',
                'updated_at' => '2022-03-02 14:40:18',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'codigo' => '3.4.2',
                'nombre' => 'PRODUCTOS QUÍMICOS Y FARMACÉUTICOS',
                'created_at' => '2022-03-02 14:40:34',
                'updated_at' => '2022-03-02 14:40:34',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'codigo' => '3.4.3',
                'nombre' => 'LLANTAS Y NEUMÁTICOS',
                'created_at' => '2022-03-02 14:40:46',
                'updated_at' => '2022-03-02 14:40:46',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'codigo' => '3.4.4',
                'nombre' => 'PRODUCTOS DE CUERO Y CAUCHO',
                'created_at' => '2022-03-02 14:41:11',
                'updated_at' => '2022-03-02 14:41:11',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'codigo' => '3.4.5',
                'nombre' => 'PRODUCTOS DE MINERALES NO METÁLICOS Y PLÁSTICOS',
                'created_at' => '2022-03-02 14:41:24',
                'updated_at' => '2022-03-02 14:41:24',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'codigo' => '3.4.6',
                'nombre' => 'PRODUCTOS METÁLICOS',
                'created_at' => '2022-03-02 14:41:35',
                'updated_at' => '2022-03-02 14:41:35',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'codigo' => '3.4.7',
                'nombre' => 'MINERALES',
                'created_at' => '2022-03-02 14:41:46',
                'updated_at' => '2022-03-02 14:41:46',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'codigo' => '3.4.8',
                'nombre' => 'HERRAMIENTAS MENORES',
                'created_at' => '2022-03-02 14:42:02',
                'updated_at' => '2022-03-02 14:42:02',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'codigo' => '3.9.1',
                'nombre' => 'MATERIAL DE LIMPIEZA E HIGIENE',
                'created_at' => '2022-03-02 14:42:20',
                'updated_at' => '2022-03-02 14:42:20',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'codigo' => '3.9.2',
                'nombre' => 'MATERIAL DEPORTIVO Y RECREATIVO',
                'created_at' => '2022-03-02 14:42:30',
                'updated_at' => '2022-03-02 14:42:30',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'codigo' => '3.9.3',
                'nombre' => 'UTENSILIOS DE COCINA Y COMEDOR',
                'created_at' => '2022-03-02 14:42:41',
                'updated_at' => '2022-03-02 14:42:41',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'codigo' => '3.9.4',
                'nombre' => 'INSTRUMENTAL MENOR MÉDICO-QUIRÚRGICO',
                'created_at' => '2022-03-02 14:42:54',
                'updated_at' => '2022-03-02 14:42:54',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'codigo' => '3.9.5',
                'nombre' => 'ÚTILES DE ESCRITORIO Y OFICINA',
                'created_at' => '2022-03-02 14:43:08',
                'updated_at' => '2022-03-02 14:43:08',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'codigo' => '3.9.6',
                'nombre' => 'ÚTILES EDUCACIONALES, CULTURALES Y DE CAPACITACIÓN',
                'created_at' => '2022-03-02 14:43:27',
                'updated_at' => '2022-03-02 14:43:27',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'codigo' => '3.9.7',
                'nombre' => 'ÚTILES Y MATERIALES ELÉCTRICOS',
                'created_at' => '2022-03-02 14:43:41',
                'updated_at' => '2022-03-02 14:43:41',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'codigo' => '3.9.8',
                'nombre' => 'OTROS REPUESTOS Y ACCESORIOS',
                'created_at' => '2022-03-02 14:43:58',
                'updated_at' => '2022-03-02 14:43:58',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'codigo' => '3.9.9.90',
                'nombre' => 'OTROS MATERIALES Y SUMINISTROS',
                'created_at' => '2022-03-02 14:44:08',
                'updated_at' => '2022-03-02 14:44:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}