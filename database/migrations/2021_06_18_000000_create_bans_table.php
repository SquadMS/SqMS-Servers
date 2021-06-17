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
        Schema::create('bans', function (Blueprint $table) {
            $table->id();

            /* The user that took the action */
            $table->foreignId('admin')->constrained('users');

            /* The server this ban does apply to */
            $table->foreignId('server')->constrained()->nullable();

            /* The user that tha action was taken against */
            $table->foreignId('user')->constrained();

            /* The reason that will be displayed ingame */
            $table->test('reason');

            /* A detailed description if neccessary */
            $table->text('description')->nullable();

            /* From to ban time, if end is null a ban is permanent */
            $table->dateTime('start');
            $table->dateTime('end')->nullable();

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
