<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonacionArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donacion_archivos', function (Blueprint $table) {
            $table->id();
            // $table->string('descripcion')->nullable();
            $table->string('nombre_origen')->nullable();
            // $table->string('extension', 10)->nullable();
            $table->string('ruta')->nullable();

            $table->foreignId('donacioningreso_id')
                  ->nullable()
                  ->constrained('donacion_ingresos')
                  ->onDelete('cascade');

            $table->foreignId('user_id')->nullable()->constrained('users');
            
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
        Schema::dropIfExists('donacion_archivos');
    }
}
