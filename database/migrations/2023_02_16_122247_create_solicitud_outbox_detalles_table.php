<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudOutboxDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_outbox_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->string('gestion', 10);

            $table->foreignId('article_id')->nullable()->constrained('article_id');
            
            $table->double('cantsolicitada',11,2)->nullable();//para que el que solicita ponga la cantidad que pide

            $table->double('cantentregada',11,2)->nullable();//para que el responsable del almacen establesca cuanto le dara



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
        Schema::dropIfExists('solicitud_outbox_detalles');
    }
}
