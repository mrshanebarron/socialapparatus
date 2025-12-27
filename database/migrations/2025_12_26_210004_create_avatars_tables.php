<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Avatar part categories (hair, eyes, nose, etc.)
        Schema::create('avatar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // Avatar parts (individual items)
        Schema::create('avatar_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('avatar_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->integer('coin_cost')->default(0);
            $table->json('color_options')->nullable(); // Available colors
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category_id', 'is_active']);
        });

        // User avatars
        Schema::create('user_avatars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('My Avatar');
            $table->json('customizations'); // Selected parts and colors
            $table->string('rendered_image')->nullable(); // Cached rendered avatar
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_primary']);
        });

        // User owned avatar parts (purchased)
        Schema::create('user_owned_avatar_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('avatar_part_id')->constrained()->cascadeOnDelete();
            $table->integer('coins_spent')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'avatar_part_id']);
        });

        // Avatar stickers (reactions using avatar)
        Schema::create('avatar_stickers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('animation_type'); // wave, laugh, cry, etc.
            $table->string('preview_path');
            $table->boolean('is_premium')->default(false);
            $table->integer('coin_cost')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avatar_stickers');
        Schema::dropIfExists('user_owned_avatar_parts');
        Schema::dropIfExists('user_avatars');
        Schema::dropIfExists('avatar_parts');
        Schema::dropIfExists('avatar_categories');
    }
};
