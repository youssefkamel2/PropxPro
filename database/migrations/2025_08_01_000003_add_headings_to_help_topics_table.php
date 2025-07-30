<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('help_topics', function (Blueprint $table) {
            $table->text('headings')->nullable()->after('content');
        });
    }

    public function down()
    {
        Schema::table('help_topics', function (Blueprint $table) {
            $table->dropColumn('headings');
        });
    }
};