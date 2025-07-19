<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDemosTable extends Migration
{
    public function up()
    {
        Schema::create('request_demos', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('real_estate_experience')->nullable();
            $table->string('monthly_budget')->nullable();
            $table->dateTime('preferred_datetime');
            $table->string('google_event_id')->nullable();
            $table->string('google_meet_link')->nullable();
            
            // Status fields with comments
            $table->string('meet_status')->default('pending')->comment('
                pending: Initial state before scheduling,
                scheduled: Meet scheduled but not confirmed,
                awaiting_confirmation: Waiting for attendee response,
                confirmed: Attendee accepted,
                declined: Attendee declined,
                completed: Demo completed,
                expired: Demo time passed without confirmation,
                cancelled: Demo cancelled,
                failed: Scheduling failed
            ');
            
            $table->string('status')->default('pending')->comment('
                pending: Initial state,
                confirmed: Demo confirmed,
                declined: Demo declined,
                completed: Demo completed,
                expired: Demo time passed without confirmation,
                cancelled: Demo cancelled,
                failed: Scheduling failed
            ');
            
            $table->text('failure_reason')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_demos');
    }
}