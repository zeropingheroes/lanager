<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLanDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @throws Exception
     */
    public function up(): void
    {
        Schema::table('lans', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lans', function (Blueprint $table) {
            $table->addColumn('description', 'text')->nullable();
        });
    }
}
