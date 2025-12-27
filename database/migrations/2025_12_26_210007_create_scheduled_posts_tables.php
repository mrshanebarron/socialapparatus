<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Scheduled posts
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('page_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->json('media')->nullable(); // Attached media paths
            $table->enum('privacy', ['public', 'friends', 'private', 'custom'])->default('public');
            $table->json('privacy_settings')->nullable(); // Custom privacy settings
            $table->timestamp('scheduled_for');
            $table->string('timezone')->default('UTC');
            $table->enum('status', ['scheduled', 'published', 'failed', 'cancelled'])->default('scheduled');
            $table->foreignId('published_post_id')->nullable(); // Reference to created post
            $table->text('failure_reason')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['scheduled_for', 'status']);
        });

        // Post schedule templates (recurring schedules)
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('schedule'); // Days/times pattern
            $table->string('timezone')->default('UTC');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        // Scheduled post drafts (auto-saved)
        Schema::create('post_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('page_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content')->nullable();
            $table->json('media')->nullable();
            $table->enum('privacy', ['public', 'friends', 'private', 'custom'])->default('public');
            $table->json('privacy_settings')->nullable();
            $table->timestamp('last_edited_at');
            $table->timestamps();

            $table->index(['user_id', 'last_edited_at']);
        });

        // Queue for optimal posting times (AI-suggested)
        Schema::create('optimal_post_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0-6
            $table->time('optimal_time');
            $table->decimal('engagement_score', 5, 2)->default(0);
            $table->integer('sample_size')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'day_of_week', 'optimal_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('optimal_post_times');
        Schema::dropIfExists('post_drafts');
        Schema::dropIfExists('schedule_templates');
        Schema::dropIfExists('scheduled_posts');
    }
};
