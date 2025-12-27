<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // login, post_create, comment, like, profile_view, etc.
            $table->string('category'); // security, content, social, settings
            $table->text('description');
            $table->nullableMorphs('subject'); // The thing that was acted on
            $table->json('properties')->nullable(); // Extra data
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'category']);
            $table->index('created_at');
        });

        // Data export requests (GDPR)
        Schema::create('data_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, processing, ready, expired
            $table->string('file_path')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Account deletion requests
        Schema::create('account_deletions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reason')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('scheduled_for'); // 30 days from request
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_deletions');
        Schema::dropIfExists('data_exports');
        Schema::dropIfExists('activity_logs');
    }
};
