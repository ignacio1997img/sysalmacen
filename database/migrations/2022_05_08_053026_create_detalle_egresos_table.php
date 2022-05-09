<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitudegreso_id')->nullable()->constrained('solicitud_egresos');
            $table->foreignId('detallefactura_id')->nullable()->constrained('detalle_facturas');
            $table->foreignId('registeruser_id')->nullable()->constrained('users');

            $table->decimal('cantsolicitada', 11, 2);
            $table->decimal('precio', 11, 2);
            $table->decimal('totalbs', 11, 2);
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
        Schema::dropIfExists('detalle_egresos');
    }
}
