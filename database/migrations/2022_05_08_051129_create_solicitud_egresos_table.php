<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_egresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->integer('unidadadministrativa')->nullable();
            $table->foreignId('registeruser_id')->nullable()->constrained('users');


            $table->string('nropedido')->nullable();
            $table->date('fechasolicitud')->nullable();
            $table->date('fechaegreso')->nullable();
            $table->string('gestion', 10)->nullable();
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
        Schema::dropIfExists('solicitud_egresos');
    }
}
