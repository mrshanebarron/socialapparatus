<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Memories - "On This Day" feature
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('memorable'); // Post, Photo, etc.
            $table->date('memory_date'); // Original date of the content
            $table->integer('years_ago');
            $table->enum('status', ['pending', 'shared', 'hidden', 'dismissed'])->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('shared_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'memory_date']);
            $table->index(['user_id', 'status']);
        });

        // Memory notification preferences
        Schema::create('memory_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('enabled')->default(true);
            $table->time('notification_time')->default('09:00:00');
            $table->json('excluded_years')->nullable(); // Years to exclude
            $table->json('excluded_people')->nullable(); // User IDs to exclude from memories
            $table->boolean('include_posts')->default(true);
            $table->boolean('include_photos')->default(true);
            $table->boolean('include_friendships')->default(true);
            $table->boolean('include_events')->default(true);
            $table->timestamps();

            $table->unique('user_id');
        });

        // Memory interactions (reactions, shares)
        Schema::create('memory_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['view', 'share', 'hide', 'dismiss', 'react']);
            $table->string('reaction_type')->nullable(); // If type is react
            $table->timestamps();

            $table->index(['memory_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memory_interactions');
        Schema::dropIfExists('memory_preferences');
        Schema::dropIfExists('memories');
    }
};
