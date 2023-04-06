<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_pedidos', function (Blueprint $table) {
            // $table->dateTime('entregadoVisto')->nullable();//para poder ver si lo ha visto la persona que ba a entregar la solicitud
            // $table->text('observation')->nullable();
            // $table->text('aprobadoObservation')->nullable();
            // $table->text('entregadoObservation')->nullable();
            // $table->text('rechazadoObservation')->nullable();
            // $table->text('deletedObservation')->nullable();


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
