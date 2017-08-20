<?php

use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->string('type');

            $table->string('action');

            $table->string('resource');

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permissions');
    }

}