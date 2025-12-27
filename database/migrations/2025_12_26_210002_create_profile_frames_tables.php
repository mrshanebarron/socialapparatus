<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profile frame categories
        Schema::create('profile_frame_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Profile frames/overlays
        Schema::create('profile_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('profile_frame_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path'); // Frame overlay image
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_animated')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->integer('coin_cost')->default(0);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('times_used')->default(0);
            $table->timestamps();

            $table->index(['category_id', 'is_active']);
            $table->index('is_premium');
        });

        // User's applied profile frames
        Schema::create('user_profile_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_frame_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('applied_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        // User's owned frames (if purchased)
        Schema::create('user_owned_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_frame_id')->constrained()->cascadeOnDelete();
            $table->timestamp('purchased_at');
            $table->integer('coins_spent')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'profile_frame_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_owned_frames');
        Schema::dropIfExists('user_profile_frames');
        Schema::dropIfExists('profile_frames');
        Schema::dropIfExists('profile_frame_categories');
    }
};
