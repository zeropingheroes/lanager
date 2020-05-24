<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogsTableDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('instance')
                ->index();
            $table->string('channel')
                ->index();
            $table->string('level')
                ->index();
            $table->string('level_name');
            $table->text('message');
            $table->longText('context');
            $table->integer('remote_addr')
                ->nullable()
                ->unsigned();
            $table->string('user_agent')
                ->nullable();
            $table->integer('created_by')
                ->nullable()
                ->index();
            $table->boolean('read')
                ->default(false);
            $table->timestamps();
        });
    }
}
