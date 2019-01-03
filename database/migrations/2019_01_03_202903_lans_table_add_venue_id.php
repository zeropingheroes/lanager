<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LansTableAddVenueId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lans', function (Blueprint $table) {
            $table->integer('venue_id')
                ->unsigned()
                ->nullable()
                ->after('id');
            $table->foreign('venue_id')
                ->references('id')
                ->on('venues')
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
            $table->dropForeign('lans_venue_id_foreign');
            $table->dropColumn('venue_id');
        });
    }
}
