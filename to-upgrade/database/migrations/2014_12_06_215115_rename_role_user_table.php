<?php

use Illuminate\Database\Migrations\Migration;

class RenameRoleUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('role_user');

        Schema::create('user_roles', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->integer('role_id')
                ->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::drop('user_roles');
        Schema::create('role_user', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->integer('role_id')
                ->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

}
