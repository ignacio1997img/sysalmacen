<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSolicitudPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_pedidos', function (Blueprint $table) {

            //aprobado
            $table->foreignId('aprobadoUser_id')->nullable()->constrained('users');
            $table->dateTime('aprobadoDate')->nullable();

            //Entregado por
            $table->foreignId('entregadoUser_id')->nullable()->constrained('users');
            $table->dateTime('entregadoDate')->nullable();

            //rechazado por
            $table->foreignId('rechazadoUser_id')->nullable()->constrained('users');
            $table->dateTime('rechazadoDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
