<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['privacy_policy', 'terms_of_service']);
            $table->longText('content');
            $table->integer('version');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            $table->unique(['type', 'version']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('legal_documents');
    }
}; 