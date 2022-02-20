<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonacionEgresoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donacion_egreso_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donacionegreso_id')->constrained('donacion_egresos');
            $table->foreignId('donacioningresodetalle_id')->constrained('donacion_ingreso_detalles');

            $table->foreignId('registeruser_id')->constrained('users');

            $table->decimal('cantentregada', 11, 2);

            $table->smallInteger('condicion')->default(1);  
            $table->timestamps();

            $table->foreignId('deleteuser_id')->nullable()->constrained('users');

            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donacion_egreso_detalles');
    }
}
