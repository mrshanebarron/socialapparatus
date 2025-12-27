<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Soundbite categories
        Schema::create('soundbite_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Soundbites (audio posts)
        Schema::create('soundbites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('soundbite_categories')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('audio_path');
            $table->string('waveform_path')->nullable(); // Visual waveform
            $table->string('cover_image')->nullable();
            $table->integer('duration_seconds');
            $table->text('transcript')->nullable();
            $table->enum('transcript_status', ['none', 'pending', 'completed', 'failed'])->default('none');
            $table->enum('privacy', ['public', 'friends', 'private'])->default('public');
            $table->integer('plays_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_duets')->default(true); // Response recordings
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'privacy']);
            $table->index(['category_id', 'is_featured']);
        });

        // Soundbite plays (listen history)
        Schema::create('soundbite_plays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soundbite_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('listened_seconds')->default(0);
            $table->boolean('completed')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['soundbite_id', 'created_at']);
        });

        // Soundbite duets (audio responses)
        Schema::create('soundbite_duets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_soundbite_id')->constrained('soundbites')->cascadeOnDelete();
            $table->foreignId('response_soundbite_id')->constrained('soundbites')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['original_soundbite_id', 'response_soundbite_id'], 'soundbite_duets_unique');
        });

        // Soundbite likes
        Schema::create('soundbite_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soundbite_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['soundbite_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soundbite_likes');
        Schema::dropIfExists('soundbite_duets');
        Schema::dropIfExists('soundbite_plays');
        Schema::dropIfExists('soundbites');
        Schema::dropIfExists('soundbite_categories');
    }
};
