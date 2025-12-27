<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Story Highlights (permanent story collections)
        Schema::create('story_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('cover_image')->nullable();
            $table->integer('position')->default(0);
            $table->integer('stories_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'position']);
        });

        // Story Highlight Items (stories in a highlight)
        Schema::create('story_highlight_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_highlight_id')->constrained()->onDelete('cascade');
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->unique(['story_highlight_id', 'story_id']);
        });

        // Add archive flag to stories
        Schema::table('stories', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('expires_at');
            $table->boolean('save_to_archive')->default(true)->after('is_archived');
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'save_to_archive']);
        });

        Schema::dropIfExists('story_highlight_items');
        Schema::dropIfExists('story_highlights');
    }
};
