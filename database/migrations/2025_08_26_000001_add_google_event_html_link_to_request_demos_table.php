<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleEventHtmlLinkToRequestDemosTable extends Migration
{
    public function up()
    {
        Schema::table('request_demos', function (Blueprint $table) {
            $table->string('google_event_html_link')->nullable()->after('google_meet_link');
        });
    }

    public function down()
    {
        Schema::table('request_demos', function (Blueprint $table) {
            $table->dropColumn('google_event_html_link');
        });
    }
}
