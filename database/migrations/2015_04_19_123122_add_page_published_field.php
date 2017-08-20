<?php

use Illuminate\Database\Migrations\Migration;

class AddPagePublishedField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function ($table) {
            $table->integer('published')
                ->default(1)
                ->unsigned()
                ->after('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function ($table) {
            $table->dropColumn('published');
        });
    }

}
