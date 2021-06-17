<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban_lists', function (Blueprint $table) {
            $table->id();

            /* A descriptive name of the Ban-List */
            $table->string('name');

            /* A description of what the Ban-List is about */
            $table->text('description')->nullable();

            /* Determines if Bans in this list apply on all servers */
            $table->boolean('global')->default(false);

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
        Schema::dropIfExists('bans');
    }
}
