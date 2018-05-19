<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lans', function ($table) {

            $table->increments('id')
                ->unsigned();

            $table->string('name');

            $table->dateTime('start');

            $table->dateTime('end');

            $table->boolean('published')
                ->default(false);

            $table->timestamps();

        });

        Schema::create('lan_attendees', function ($table) {

            $table->increments('id');

            $table->integer('lan_id')
                ->unsigned();

            $table->integer('user_id')
                ->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lan_attendees');
        Schema::drop('lans');
    }
}
