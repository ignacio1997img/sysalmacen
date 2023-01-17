<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistInvDeletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_inv_deletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->nullable()->constrained('inventario_almacens');
            
            $table->string('gestion', 10);

            $table->date('start')->nullable();
            $table->foreignId('startUser_id')->nullable()->constrained('users');

            $table->date('finish')->nullable();
            $table->foreignId('finishUser_id')->nullable()->constrained('users');


            $table->text('observation')->nullable();
            $table->text('observation1')->nullable();

            $table->text('deleteObservation')->nullable();

            $table->string('status')->default(1);
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('deleteUser_id')->nullable()->constrained('users');

            $table->foreignId('registeruser_id')->nullable()->constrained('users');
            $table->text('nameFile')->nullable();
            $table->text('routeFile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hist_inv_deletes');
    }
}
