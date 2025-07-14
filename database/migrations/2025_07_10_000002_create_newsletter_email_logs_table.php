<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsletterEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::create('newsletter_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->enum('status', ['sent', 'failed']);
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('subscriber_id')->references('id')->on('newsletter_subscriptions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('newsletter_email_logs');
    }
} 