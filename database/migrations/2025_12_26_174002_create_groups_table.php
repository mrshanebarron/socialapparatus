<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->enum('privacy', ['public', 'private', 'secret'])->default('public');
            $table->enum('join_approval', ['auto', 'admin'])->default('auto');
            $table->boolean('allow_member_posts')->default(true);
            $table->boolean('allow_member_invites')->default(true);
            $table->unsignedInteger('members_count')->default(0);
            $table->unsignedInteger('posts_count')->default(0);
            $table->timestamps();

            $table->index('privacy');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
