<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // User interests for recommendations
        Schema::create('user_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('interest'); // topic/category name
            $table->integer('score')->default(1); // engagement score
            $table->string('source')->nullable(); // post_like, group_join, hashtag_follow
            $table->timestamps();

            $table->unique(['user_id', 'interest']);
        });

        // Trending topics/hashtags
        Schema::create('trends', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // hashtag, topic, search
            $table->integer('mentions_count')->default(0);
            $table->integer('hourly_count')->default(0);
            $table->integer('daily_count')->default(0);
            $table->string('category')->nullable();
            $table->json('related_hashtags')->nullable();
            $table->timestamp('trending_since')->nullable();
            $table->timestamps();

            $table->unique(['name', 'type']);
            $table->index('daily_count');
        });

        // Personalized recommendations
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('recommendable'); // User, Group, Page, Post
            $table->string('type'); // people_you_may_know, suggested_groups, suggested_pages, for_you
            $table->decimal('score', 5, 2)->default(0); // recommendation score
            $table->string('reason')->nullable(); // "5 mutual friends", "Based on your interests"
            $table->boolean('is_dismissed')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'type', 'is_dismissed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('trends');
        Schema::dropIfExists('user_interests');
    }
};
