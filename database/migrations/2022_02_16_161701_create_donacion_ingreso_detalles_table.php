<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonacionIngresoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donacion_ingreso_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donacioningreso_id')->constrained('donacion_ingresos');
            $table->foreignId('donacionarticulo_id')->constrained('donacion_articulos');
            $table->foreignId('registeruser_id')->nullable()->constrained('users');

            $table->string('estado');
            $table->text('caracteristica')->nullable();
            $table->decimal('cantidad', 11, 2);
            $table->decimal('precio', 11, 2)->default(0);
            $table->decimal('cantrestante', 11, 2);

            $table->date('caducidad')->nullable(); 
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
        Schema::dropIfExists('donacion_ingreso_detalles');
    }
}
