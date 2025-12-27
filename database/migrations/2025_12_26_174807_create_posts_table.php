<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('body')->nullable();
            $table->enum('type', ['text', 'photo', 'video', 'link', 'poll'])->default('text');
            $table->json('media')->nullable();
            $table->string('link_url')->nullable();
            $table->json('link_preview')->nullable();
            $table->enum('visibility', ['public', 'friends', 'private'])->default('public');
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('shares_count')->default(0);
            $table->foreignId('shared_post_id')->nullable()->constrained('posts')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
            $table->index(['group_id', 'created_at']);
            $table->index(['visibility', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
