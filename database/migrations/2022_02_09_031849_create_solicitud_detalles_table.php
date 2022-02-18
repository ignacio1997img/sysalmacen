<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicituds');
            $table->foreignId('solicitudcompra_id')->constrained('solicitud_compras');
            $table->foreignId('detallefactura_id')->constrained('detalle_facturas');  

            $table->decimal('cantidad', 11, 2);
            $table->decimal('cantidadentregar', 11, 2)->default(0);

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
        Schema::dropIfExists('solicitud_detalles');
    }
}
