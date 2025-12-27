<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Content Series (like YouTube playlists or Netflix series)
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('content_type')->default('mixed'); // mixed, video, article, post
            $table->string('privacy')->default('public'); // public, friends, private
            $table->boolean('is_featured')->default(false);
            $table->integer('items_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('followers_count')->default(0);
            $table->boolean('auto_play')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'privacy']);
        });

        // Series Items (episodes/parts)
        Schema::create('series_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->morphs('content'); // Post, Article, Media (video)
            $table->integer('episode_number');
            $table->string('custom_title')->nullable();
            $table->text('custom_description')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamps();

            $table->unique(['series_id', 'episode_number']);
        });

        // Series Followers
        Schema::create('series_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('notify_new_episodes')->default(true);
            $table->timestamps();

            $table->unique(['series_id', 'user_id']);
        });

        // Series Watch Progress
        Schema::create('series_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('current_item_id')->nullable()->constrained('series_items')->onDelete('set null');
            $table->integer('current_position_seconds')->default(0);
            $table->integer('items_completed')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();

            $table->unique(['series_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series_progress');
        Schema::dropIfExists('series_followers');
        Schema::dropIfExists('series_items');
        Schema::dropIfExists('series');
    }
};
