<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanTableCreate extends Migration
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lans');
    }
}
