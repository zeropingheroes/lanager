<?php

use Illuminate\Database\Migrations\Migration;

class CreateSessionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function ($table) {
            // Fields
            $table->string('id')
                ->unique();

            $table->text('payload');

            $table->integer('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }

}
