<?php

use Illuminate\Database\Migrations\Migration;

class AddEventPublishedField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function ($table) {
            $table->integer('published')
                ->default(1)
                ->unsigned()
                ->after('event_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function ($table) {
            $table->dropColumn('published');
        });
    }

}
