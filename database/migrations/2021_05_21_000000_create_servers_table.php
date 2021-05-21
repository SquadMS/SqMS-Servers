<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();

            /* Name of the server */
            $table->string('name')->unique();

            /* Determines if the servers playtime accounts to general playtime */
            $table->string('account_playtime')->unique();

            /* Connection Configuration */
            $table->string('host')->unique();
            $table->unsignedSmallInteger('game_port')->default(7787);
            $table->unsignedSmallInteger('query_port')->default(27165);
            $table->unique(['host', 'game_port']);
            $table->unique(['host', 'query_port']);
          
            /* RCON Config */
            $table->unsignedSmallInteger('rcon_port')->default(21115);
            $table->unsignedSmallInteger('rcon_password');
            $table->unique(['host', 'rcon_port']);

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
        Schema::dropIfExists('servers');
    }
}
