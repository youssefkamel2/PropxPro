<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('webinar_event_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('webinar_events')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('webinar_event_registrations');
    }
};