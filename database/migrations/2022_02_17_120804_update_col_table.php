<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donacion_ingreso_detalles', function (Blueprint $table) {
            $table->smallInteger('condicion')->default(1); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donacion_ingreso_detalles', function (Blueprint $table) {
            $table->dropColumn('condicion');
        });
    }
}
