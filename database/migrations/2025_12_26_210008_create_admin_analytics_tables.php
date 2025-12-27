<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daily platform metrics
        Schema::create('platform_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('new_users')->default(0);
            $table->integer('active_users')->default(0);
            $table->integer('returning_users')->default(0);
            $table->integer('total_posts')->default(0);
            $table->integer('total_comments')->default(0);
            $table->integer('total_reactions')->default(0);
            $table->integer('total_messages')->default(0);
            $table->integer('total_media_uploads')->default(0);
            $table->bigInteger('storage_used_bytes')->default(0);
            $table->integer('new_groups')->default(0);
            $table->integer('new_events')->default(0);
            $table->decimal('avg_session_minutes', 8, 2)->default(0);
            $table->json('top_content')->nullable();
            $table->json('demographics')->nullable();
            $table->timestamps();
        });

        // Content performance metrics
        Schema::create('content_analytics', function (Blueprint $table) {
            $table->id();
            $table->morphs('content'); // Post, Photo, Video, etc.
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('reactions')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('saves')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('engagement_rate', 5, 2)->default(0);
            $table->integer('reach')->default(0);
            $table->integer('impressions')->default(0);
            $table->timestamps();

            $table->unique(['content_type', 'content_id', 'date']);
        });

        // User engagement trends
        Schema::create('engagement_trends', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('metric_type'); // posts, comments, reactions, etc.
            $table->string('segment')->nullable(); // age_group, location, etc.
            $table->string('segment_value')->nullable();
            $table->integer('count')->default(0);
            $table->decimal('change_percent', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['date', 'metric_type']);
        });

        // Admin insights/alerts
        Schema::create('admin_insights', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // growth, engagement, warning, opportunity
            $table->string('title');
            $table->text('description');
            $table->json('data')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['new', 'viewed', 'actioned', 'dismissed'])->default('new');
            $table->foreignId('viewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['priority', 'status']);
        });

        // Real-time dashboard metrics cache
        Schema::create('dashboard_cache', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        // User retention cohorts
        Schema::create('retention_cohorts', function (Blueprint $table) {
            $table->id();
            $table->date('cohort_date'); // Week/month of registration
            $table->string('cohort_type')->default('weekly'); // weekly, monthly
            $table->integer('period'); // 0 = registration week, 1 = next week, etc.
            $table->integer('cohort_size')->default(0);
            $table->integer('retained_users')->default(0);
            $table->decimal('retention_rate', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['cohort_date', 'cohort_type', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retention_cohorts');
        Schema::dropIfExists('dashboard_cache');
        Schema::dropIfExists('admin_insights');
        Schema::dropIfExists('engagement_trends');
        Schema::dropIfExists('content_analytics');
        Schema::dropIfExists('platform_metrics');
    }
};
