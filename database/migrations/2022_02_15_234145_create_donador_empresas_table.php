<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonadorEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donador_empresas', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tipo');
            $table->string('nit');
            $table->string('razon',500);
            $table->string('tel')->nullable();
            $table->text('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('responsable')->nullable();


            $table->smallInteger('condicion')->default(1); 
            
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
        Schema::dropIfExists('donador_empresas');
    }
}
