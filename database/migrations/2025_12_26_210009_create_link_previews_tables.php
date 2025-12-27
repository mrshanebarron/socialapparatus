<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cached link previews
        Schema::create('link_previews', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->string('url_hash')->unique(); // MD5 hash for indexing
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->string('image_path')->nullable(); // Locally cached image
            $table->string('site_name')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('type')->default('website'); // website, article, video, etc.
            $table->string('video_url', 2048)->nullable(); // For video embeds
            $table->json('metadata')->nullable(); // Additional OG/meta data
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('url_hash');
            $table->index(['status', 'expires_at']);
        });

        // Link preview usage (which posts use which preview)
        Schema::create('link_preview_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_preview_id')->constrained()->cascadeOnDelete();
            $table->morphs('linkable'); // Post, Comment, Message, etc. (creates index automatically)
            $table->timestamps();
        });

        // Link click tracking
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_preview_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->morphs('source'); // Where the click came from
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->timestamps();

            $table->index(['link_preview_id', 'created_at']);
        });

        // Blocked/unsafe domains
        Schema::create('blocked_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('reason')->nullable();
            $table->foreignId('blocked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Domain trust scores
        Schema::create('domain_trust_scores', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->decimal('trust_score', 3, 2)->default(0.5); // 0-1
            $table->integer('times_shared')->default(0);
            $table->integer('times_reported')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_trust_scores');
        Schema::dropIfExists('blocked_domains');
        Schema::dropIfExists('link_clicks');
        Schema::dropIfExists('link_preview_usages');
        Schema::dropIfExists('link_previews');
    }
};
