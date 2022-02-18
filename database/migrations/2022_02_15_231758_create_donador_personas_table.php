<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonadorPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donador_personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci');
            $table->string('nombre',500);
            $table->string('tel')->nullable();
            $table->text('direccion')->nullable();

            $table->smallInteger('condicion')->default(1); 
            
            $table->timestamps();
            
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
        Schema::dropIfExists('donador_personas');
    }
}
