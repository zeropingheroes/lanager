<?php

use Illuminate\Database\Migrations\Migration;

class RemoveAchievementImageField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievements', function ($table) {
            $table->dropColumn('image');
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
            $table->text('image')
                ->nullable();
        });
    }

}
