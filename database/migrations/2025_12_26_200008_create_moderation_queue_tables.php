<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Moderation Queue
        Schema::create('moderation_queue', function (Blueprint $table) {
            $table->id();
            $table->morphs('moderatable'); // Comment, Post, Message, etc.
            $table->string('reason'); // auto_flagged, reported, keyword_match, spam_detected
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, escalated
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->integer('report_count')->default(1);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('moderator_notes')->nullable();
            $table->string('action_taken')->nullable(); // none, warning, hidden, deleted, banned
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority', 'created_at']);
        });

        // Auto-moderation Rules
        Schema::create('moderation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // keyword, regex, spam_score, user_reputation
            $table->text('pattern')->nullable(); // The rule pattern
            $table->string('action')->default('queue'); // queue, auto_reject, auto_approve, hide
            $table->string('priority')->default('normal');
            $table->string('applies_to')->default('all'); // all, comments, posts, messages
            $table->boolean('is_active')->default(true);
            $table->integer('trigger_count')->default(0);
            $table->timestamps();
        });

        // Moderation Actions Log
        Schema::create('moderation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderation_queue_id')->nullable()->constrained('moderation_queue')->onDelete('set null');
            $table->foreignId('moderator_id')->constrained('users')->onDelete('cascade');
            $table->morphs('target'); // The content that was moderated
            $table->foreignId('target_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // approved, rejected, hidden, deleted, warned, banned
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['moderator_id', 'created_at']);
        });

        // User Warnings
        Schema::create('user_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('moderation_queue_id')->nullable()->constrained('moderation_queue')->onDelete('set null');
            $table->string('type'); // content_violation, spam, harassment, misinformation
            $table->text('message');
            $table->integer('severity')->default(1); // 1-5 scale
            $table->boolean('acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Add moderation fields to comments
        Schema::table('comments', function (Blueprint $table) {
            $table->string('moderation_status')->default('approved')->after('body'); // pending, approved, rejected, hidden
            $table->boolean('requires_moderation')->default(false)->after('moderation_status');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['moderation_status', 'requires_moderation']);
        });

        Schema::dropIfExists('user_warnings');
        Schema::dropIfExists('moderation_actions');
        Schema::dropIfExists('moderation_rules');
        Schema::dropIfExists('moderation_queue');
    }
};
