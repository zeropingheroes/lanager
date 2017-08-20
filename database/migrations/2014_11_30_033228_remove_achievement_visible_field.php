<?php

use Illuminate\Database\Migrations\Migration;

class RemoveAchievementVisibleField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievements', function ($table) {
            $table->dropColumn('visible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function ($table) {
            $table->tinyInteger('visible')
                ->default(1)
                ->unsigned();
        });
    }

}
