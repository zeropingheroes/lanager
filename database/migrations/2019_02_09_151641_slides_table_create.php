<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SlidesTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lan_id')
                ->unsigned();
            $table->string('name')
                ->unique();
            $table->text('content');
            $table->tinyInteger('position');
            $table->smallInteger('duration');
            $table->boolean('published')
                ->default(false);
            $table->timestamps();
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
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
        Schema::dropIfExists('slides');

    }
}
