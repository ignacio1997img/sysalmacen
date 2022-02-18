<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitudcompra_id')->constrained('solicitud_compras');
            $table->foreignId('provider_id')->constrained('providers');
            // $table->foreignId('modality_id')->constrained('modalities');
            $table->foreignId('registeruser_id')->constrained('users');

            $table->string('tipofactura');
            $table->date('fechafactura')->nullable();
            $table->decimal('montofactura', 11, 2);
            $table->string('nrofactura')->nullable();
            $table->string('nroautorizacion')->nullable();
            $table->string('nrocontrol')->nullable();

            $table->date('fechaingreso');
            $table->string('gestion', 10);
            $table->boolean('condicion')->default(1); 
            $table->timestamps();


            $table->unsignedBigInteger('deleteuser_id')->nullable(); 

            $table->foreign('deleteuser_id')->references('id')->on('users');

            // $table->foreignId('deleteuser_id')->constrained('users')->nullable();
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
        Schema::dropIfExists('facturas');
    }
}
