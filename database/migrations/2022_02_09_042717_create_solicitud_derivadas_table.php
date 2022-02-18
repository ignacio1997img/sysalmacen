<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudDerivadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_derivadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicituds');

            $table->foreignId('de_id')->constrained('users'); 
            $table->string('de_nombre');

            $table->foreignId('dirigido_id')->constrained('users'); 
            $table->string('dirigido_nombre');

            $table->text('detalles')->nullable();

            $table->boolean('rechazado')->default(0); 
            $table->boolean('aprobado')->default(0); 
            $table->date('fechapr')->nullable();
            
            $table->boolean('atendido')->default(0);

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
        Schema::dropIfExists('solicitud_derivadas');
    }
}
