<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->string('nombre_origen')->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('ruta')->nullable();

            $table->foreignId('factura_id')
                  ->nullable()
                  ->constrained('facturas')
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
        Schema::dropIfExists('archivos');
    }
}
