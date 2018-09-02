<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventSignupsTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_signups', function (Blueprint $table) {
            // Fields
            $table->increments('id');
            $table->integer('event_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->timestamps();

            // Relationships
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('signup_opens', 'signups_open');
            $table->renameColumn('signup_closes', 'signups_close');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('signups_open', 'signup_opens');
            $table->renameColumn('signups_close', 'signup_closes');
        });
        Schema::drop('event_signups');
    }
}
