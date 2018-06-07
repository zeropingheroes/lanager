<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagesTableCreate extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages',
            function ($table) {
                // Fields
                $table->increments('id');

                $table->integer('lan_id')
                    ->unsigned();

                $table->string('title');

                $table->text('content')
                    ->nullable();

                $table->boolean('published')
                    ->default(false);

                $table->timestamps();

                // Relationships
                $table->foreign('lan_id')
                    ->references('id')
                    ->on('lans')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
