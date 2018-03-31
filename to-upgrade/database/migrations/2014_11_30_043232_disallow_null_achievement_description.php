<?php

use Illuminate\Database\Migrations\Migration;

class DisallowNullAchievementDescription extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE achievements MODIFY COLUMN description VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE achievements MODIFY COLUMN description VARCHAR(255) DEFAULT NULL');
    }

}
