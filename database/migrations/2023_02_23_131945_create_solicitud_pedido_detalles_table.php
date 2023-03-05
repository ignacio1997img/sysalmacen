<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudPedidoDetallesTable extends Migration
{
    public function up()
    {
        Schema::create('solicitud_pedido_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitudPedido_id')->nullable()->constrained('solicitud_pedidos');

            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->string('gestion', 10);

            $table->foreignId('article_id')->nullable()->constrained('articles');
            
            $table->double('cantsolicitada',11,2)->nullable();//para que el que solicita ponga la cantidad que pide

            $table->double('cantentregada',11,2)->default(0);//para que el responsable del almacen establesca cuanto le dara
            // $table->text('details')->nullable();
            $table->text('jsonDetails_id')->nullable();
            $table->text('jsonCant')->nullable();


            $table->smallInteger('status')->default(1);
            $table->foreignId('registerUser_Id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('deletedUser_Id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud_pedido_detalles');
    }
}
