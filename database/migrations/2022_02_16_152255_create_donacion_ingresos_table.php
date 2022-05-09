<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonacionIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donacion_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_id')->constrained('centros');

            $table->integer('tipodonante')->nullable();
            $table->integer('onuempresa_id')->nullable();
            $table->integer('persona_id')->nullable();

            $table->foreignId('registeruser_id')->nullable()->constrained('users');

            $table->string('nrosolicitud')->nullable();
            $table->date('fechadonacion')->nullable();
            $table->date('fechaingreso')->nullable();

            $table->text('observacion')->nullable();
            $table->string('gestion', 10)->nullable();
            $table->smallInteger('condicion')->default(1);  
            $table->smallInteger('stock')->default(1);
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
        Schema::dropIfExists('donacion_ingresos');
    }
}
