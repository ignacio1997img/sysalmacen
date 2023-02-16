<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudOutboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_outboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            $table->date('fechasolicitud');
            $table->string('gestion', 10);
            $table->string('nropedido')->nullable();

            $table->integer('people_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('job')->nullable();
            $table->string('direccion_name')->nullable();
            $table->integer('direccion_id')->nullable();
            $table->string('direccion_name')->nullable();
            $table->integer('unidad_id')->nullable();

            
            $table->smallInteger('status')->default('Pendiente');
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
        Schema::dropIfExists('solicitud_outboxes');
    }
}
