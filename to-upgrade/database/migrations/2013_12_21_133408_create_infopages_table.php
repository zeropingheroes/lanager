<?php

use Illuminate\Database\Migrations\Migration;

class CreateInfopagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_pages', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('parent_id')
                ->nullable()
                ->unsigned();

            $table->string('title');

            $table->text('content')
                ->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('parent_id')
                ->references('id')
                ->on('info_pages')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('info_pages');
    }

}