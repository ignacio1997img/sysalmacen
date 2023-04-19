<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->nullable()->constrained('chats');
            $table->string('type')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->text('message')->nullable();
            $table->smallInteger('view')->default(0);
            $table->dateTime('viewDate')->nullable();


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
        Schema::dropIfExists('chats_lines');
    }
}
