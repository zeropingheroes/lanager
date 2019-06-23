<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LansTableAddAchievementId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lans', function (Blueprint $table) {
            $table->integer('achievement_id')
                ->unsigned()
                ->nullable()
                ->after('venue_id');
            $table->foreign('achievement_id')
                ->references('id')
                ->on('achievements')
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
        Schema::table('lans', function (Blueprint $table) {
            $table->dropForeign('lans_achievement_id_foreign');
            $table->dropColumn('achievement_id');
        });
    }
}
