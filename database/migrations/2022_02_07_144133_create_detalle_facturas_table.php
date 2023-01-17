<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas');
            $table->foreignId('registeruser_id')->constrained('users');
            $table->foreignId('article_id')->constrained('articles');

            $table->decimal('cantsolicitada', 11, 2);
            $table->decimal('precio', 11, 2);
            $table->decimal('totalbs', 11, 2);
            $table->decimal('cantrestante', 11, 2);

            $table->date('fechaingreso');
            $table->string('gestion', 10);

            $table->decimal('histcantsolicitada', 11, 2)->nullable();
            $table->decimal('histprecio', 11, 2)->nullable();
            $table->decimal('histtotalbs', 11, 2)->nullable();
            $table->decimal('histcantrestante', 11, 2)->nullable();
            $table->date('histfechaingreso')->nullable();

            $table->string('parent_id')->nullable();

            $table->string('histgestion', 10)->nullable();
            $table->integer('hist')->default(0);

            $table->boolean('condicion')->default(1);  //si el articulo se encuentra vigente 
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            
            $table->timestamps();
            $table->foreignId('deleteuser_id')->nullable()->constrained('users');
            $table->softDeletes();
            $table->text('deleteObservation')->nullable();
            $table->foreignId('HistInvDelete_id')->nullable()->constrained('hist_inv_deletes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_facturas');
    }
}
