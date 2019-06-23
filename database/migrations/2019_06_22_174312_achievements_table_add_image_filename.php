<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AchievementsTableAddImageFilename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->string('image_filename')
                ->nullable()
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('image_filename');
        });
    }
}
