<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicituds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursals');
            $table->integer('unidadadministrativa');
            $table->foreignId('registeruser_id')->constrained('users');
            $table->string('cargo',500);
            $table->string('nroproceso');
            $table->date('fechasolicitud');
            $table->string('gestion', 10);

            $table->string('estado')->default('Creado');
            $table->string('atendidopor', 500)->nullable();

            $table->boolean('derivado')->default(0); 
            $table->boolean('condicion')->default(1); 
            
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
        Schema::dropIfExists('solicituds');
    }
}
