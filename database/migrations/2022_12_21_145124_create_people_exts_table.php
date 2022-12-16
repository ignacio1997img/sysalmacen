<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleExtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_exts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('people_id')->nullable();
            $table->bigInteger('direccionAdministrativa_id')->nullable();
            $table->string('cargo')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->text('observation')->nullable();
            $table->smallInteger('status')->default(1);
            $table->foreignId('registerUser_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('people_exts');
    }
}
