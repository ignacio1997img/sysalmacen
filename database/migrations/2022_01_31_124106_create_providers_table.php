<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 20);
            $table->string('razonsocial', 512);
            $table->string('responsable', 512)->nullable();
            $table->string('direccion', 512)->nullable();
            $table->string('telefono', 512)->nullable();
            $table->string('fax', 512)->nullable();
            $table->string('comentario', 512)->nullable();
            $table->boolean('condicion')->default(1);
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->timestamps();
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
        Schema::dropIfExists('providers');
    }
}
