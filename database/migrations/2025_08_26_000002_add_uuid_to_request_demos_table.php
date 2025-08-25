<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToRequestDemosTable extends Migration
{
    public function up()
    {
        // Add the UUID column
        Schema::table('request_demos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Generate UUIDs for existing records
        \DB::table('request_demos')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunk(100, function ($demos) {
                foreach ($demos as $demo) {
                    \DB::table('request_demos')
                        ->where('id', $demo->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });

        // Make the UUID column non-nullable after populating
        Schema::table('request_demos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid');
        });
    }

    public function down()
    {
        Schema::table('request_demos', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
