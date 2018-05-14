<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventsTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function ($table) {
            // Fields
            $table->increments('id');

            $table->string('name');

            $table->string('colour')
                ->nullable();

            $table->timestamps();

        });

        Schema::create('events', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('lan_id')
                ->unsigned();

            $table->integer('event_type_id')
                ->unsigned();

            $table->string('name');

            $table->text('description')
                ->nullable();

            $table->boolean('published')
                ->default(false);

            $table->dateTime('start');

            $table->dateTime('end');

            $table->dateTime('signup_opens')
                ->nullable();

            $table->dateTime('signup_closes')
                ->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
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
        Schema::drop('events');
        Schema::drop('event_types');
    }
}
