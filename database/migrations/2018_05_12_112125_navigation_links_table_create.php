<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NavigationLinksTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'navigation_links',
            function ($table) {
                // Fields
                $table->increments('id');

                $table->string('title');

                $table->tinyInteger('position');

                $table->text('url');

                $table->integer('parent_id')
                    ->nullable()
                    ->unsigned();

                $table->timestamps();

                // Relationships
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('pages')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
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
        Schema::drop('navigation_links');
    }
}
