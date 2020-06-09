<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhpdebugbarTableChangeMetaUriToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->renameColumn('meta_uri', 'meta_uri_old');
        });
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->text('meta_uri');
        });

        // Copy existing data over
        DB::table('phpdebugbar')
            ->update([
                'meta_uri' => DB::raw('`meta_uri_old`'),
            ]);

        // Drop old column
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->dropColumn('meta_uri_old');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->renameColumn('meta_uri', 'meta_uri_old');
        });
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->text('meta_uri');
        });

        // Copy existing data over
        DB::table('phpdebugbar')
            ->update([
                'meta_uri' => DB::raw('`meta_uri_old`'),
            ]);

        // Drop old column
        Schema::table('phpdebugbar', function (Blueprint $table) {
            $table->dropColumn('meta_uri_old');
        });
    }
}
