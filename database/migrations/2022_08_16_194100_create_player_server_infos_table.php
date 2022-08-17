<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerServerInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_server_infos', function (Blueprint $table) {
            $table->id();

            /* The server this message was sent on */
            $table->foreignId('user_id')->constrained();

            /* The server this message was sent on */
            $table->foreignId('server_id')->constrained();

            /* Minutes spent on the server */
            $table->unsignedBigInteger('playtime')->default(0);

            /* Minutes spent during configured seeding treshold */
            $table->unsignedBigInteger('seedingtime')->default(0);

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
        Schema::dropIfExists('player_server_infos');
    }
}
