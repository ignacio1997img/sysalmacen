<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->date('fechasolicitud');
            $table->string('gestion', 10);
            $table->string('nropedido')->nullable();

            $table->integer('people_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('job')->nullable();
            $table->string('direccion_name')->nullable();
            $table->integer('direccion_id')->nullable();
            $table->string('unidad_name')->nullable();
            $table->integer('unidad_id')->nullable();
            $table->dateTime('visto')->nullable();

            
            $table->string('status')->default('Pendiente');
            $table->text('observation')->nullable();
            $table->foreignId('registerUser_Id')->nullable()->constrained('users');
            $table->timestamps();

            //aprobado
            $table->foreignId('aprobadoUser_id')->nullable()->constrained('users');
            $table->dateTime('aprobadoDate')->nullable();
            $table->text('aprobadoObservation')->nullable();

            //Entregado por
            $table->dateTime('entregadoVisto')->nullable();//para poder ver si lo ha visto la persona que ba a entregar la solicitud
            $table->foreignId('entregadoUser_id')->nullable()->constrained('users');
            $table->dateTime('entregadoDate')->nullable();
            $table->text('entregadoObservation')->nullable();

            //rechazado por
            $table->foreignId('rechazadoUser_id')->nullable()->constrained('users');
            $table->dateTime('rechazadoDate')->nullable();
            $table->text('rechazadoObservation')->nullable();
            
            $table->softDeletes();
            $table->foreignId('deletedUser_Id')->nullable()->constrained('users');
            $table->text('deletedObservation')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud_pedidos');
    }
}
