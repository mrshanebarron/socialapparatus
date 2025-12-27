<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Digest Preferences
        Schema::create('digest_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->string('frequency')->default('weekly'); // daily, weekly, monthly
            $table->string('day_of_week')->default('monday'); // For weekly
            $table->time('preferred_time')->default('09:00:00');
            $table->string('timezone')->default('UTC');
            $table->boolean('include_trending')->default(true);
            $table->boolean('include_friend_activity')->default(true);
            $table->boolean('include_group_activity')->default(true);
            $table->boolean('include_events')->default(true);
            $table->boolean('include_memories')->default(true);
            $table->timestamps();

            $table->unique('user_id');
        });

        // Sent Digests (for tracking)
        Schema::create('sent_digests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // daily, weekly, monthly
            $table->date('period_start');
            $table->date('period_end');
            $table->json('content_summary')->nullable(); // Summary of what was included
            $table->integer('items_count')->default(0);
            $table->string('status')->default('sent'); // queued, sent, failed, opened
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'period_start']);
        });

        // Digest Items (content included in digest)
        Schema::create('digest_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_digest_id')->constrained()->onDelete('cascade');
            $table->morphs('digestable'); // Post, Article, Event, etc.
            $table->string('category'); // trending, friend_activity, group, event, memory
            $table->integer('position');
            $table->integer('engagement_score')->default(0);
            $table->timestamps();
        });

        // Add digest settings to users if not exists
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('digest_enabled')->default(true)->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('digest_enabled');
        });

        Schema::dropIfExists('digest_items');
        Schema::dropIfExists('sent_digests');
        Schema::dropIfExists('digest_preferences');
    }
};
