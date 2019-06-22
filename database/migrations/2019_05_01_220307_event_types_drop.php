<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventTypesDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_event_type_id_foreign');
            $table->dropColumn('event_type_id');
        });
        Schema::drop('event_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('colour')
                ->nullable();
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->integer('event_type_id')
                ->unsigned()
                ->nullable(); // Have to make it nullable or rollback fails due to events with no event type ID
            $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
