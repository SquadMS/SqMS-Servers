<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_chat_messages', function (Blueprint $table) {
            $table->id();

            /* The server this message was sent on */
            $table->foreignId('server')->constrained();

            /* The user that sent the message */
            $table->foreignId('user')->constrained();

            /* Type / Chat this was sent on */
            $table->string('type')->index();

            /* Username used to send the message */
            $table->text('name');

            /* Content of the message */
            $table->text('content');

            /* Time it was sent on the server */
            $table->dateTime('time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_chat_messages');
    }
}
