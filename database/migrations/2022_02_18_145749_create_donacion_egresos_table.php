<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonacionEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donacion_egresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_id')->constrained('centros');
            // $table->foreignId('donacioningresodetalle_id')->constrained('donacion_ingreso_detalles');

            $table->foreignId('registeruser_id')->constrained('users');

            $table->string('nrosolicitud');
            $table->date('fachaentrega');

            // $table->decimal('cantentregada', 11, 2);

            $table->text('observacion')->nullable();
            $table->string('gestion', 10);
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
        Schema::dropIfExists('donacion_egresos');
    }
}
