<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioAlmacensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_almacens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursals');
            
            $table->string('gestion', 10);

            $table->date('start')->nullable();
            $table->foreignId('startUser_id')->nullable()->constrained('users');

            $table->date('finish')->nullable();
            $table->foreignId('finishUser_id')->nullable()->constrained('users');


            $table->text('observation')->nullable();
            $table->text('observation1')->nullable();

            $table->string('status')->default(1);
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('deleteUser_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventario_almacens');
    }
}
