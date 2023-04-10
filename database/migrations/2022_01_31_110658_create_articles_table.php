<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->nullable()->constrained('partidas');
            $table->string('nombre', 512);
            $table->text('image')->nullable();

            // $table->string('marca', 512);
            $table->string('presentacion', 512);
            $table->boolean('condicion')->default(1);
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
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
        Schema::dropIfExists('articles');
    }
}
