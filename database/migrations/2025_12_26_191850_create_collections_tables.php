<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Collections (like Pinterest boards)
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('visibility')->default('public'); // public, friends, private
            $table->string('type')->default('general'); // general, watch_later, reading_list, favorites
            $table->boolean('is_collaborative')->default(false);
            $table->integer('items_count')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
        });

        // Collection items (polymorphic - can hold posts, articles, videos, etc.)
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->morphs('collectable'); // Post, Article, etc.
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['collection_id', 'collectable_type', 'collectable_id'], 'collection_items_unique');
        });

        // Collection collaborators
        Schema::create('collection_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('contributor'); // contributor, editor
            $table->timestamps();

            $table->unique(['collection_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_collaborators');
        Schema::dropIfExists('collection_items');
        Schema::dropIfExists('collections');
    }
};
