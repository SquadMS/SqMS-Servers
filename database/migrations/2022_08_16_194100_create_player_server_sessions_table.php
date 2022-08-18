<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerServerSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_server_sessions', function (Blueprint $table) {
            $table->id();

            /* The server this message was sent on */
            $table->foreignId('user_id')->constrained();

            /* The server this message was sent on */
            $table->foreignId('server_id')->constrained();

            /* The start and end of the session */
            $table->timestamp('joined_at');
            $table->timestamp('left_at');

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
        Schema::dropIfExists('player_server_sessions');
    }
}
