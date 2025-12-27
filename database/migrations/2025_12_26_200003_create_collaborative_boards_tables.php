<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Boards (collaborative workspaces)
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('boardable'); // Group, Event, Page, or null for personal
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('privacy')->default('private'); // private, collaborators, public
            $table->string('type')->default('general'); // general, kanban, checklist, resources
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'privacy']);
        });

        // Board Columns (for kanban-style boards)
        Schema::create('board_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('position')->default(0);
            $table->integer('items_count')->default(0);
            $table->timestamps();
        });

        // Board Items (cards/tasks/resources)
        Schema::create('board_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('board_column_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('card'); // card, task, link, file, note
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('due_date')->nullable();
            $table->string('priority')->nullable(); // low, medium, high, urgent
            $table->timestamps();

            $table->index(['board_id', 'board_column_id', 'position']);
        });

        // Board Collaborators
        Schema::create('board_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('viewer'); // viewer, editor, admin
            $table->timestamp('invited_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->unique(['board_id', 'user_id']);
        });

        // Board Item Assignees
        Schema::create('board_item_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['board_item_id', 'user_id']);
        });

        // Board Item Comments
        Schema::create('board_item_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_item_comments');
        Schema::dropIfExists('board_item_assignees');
        Schema::dropIfExists('board_collaborators');
        Schema::dropIfExists('board_items');
        Schema::dropIfExists('board_columns');
        Schema::dropIfExists('boards');
    }
};
