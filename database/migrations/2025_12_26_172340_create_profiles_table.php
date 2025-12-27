<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic profile info
            $table->string('username')->unique()->nullable();
            $table->string('display_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();

            // Media
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();

            // Privacy settings
            $table->enum('profile_visibility', ['public', 'friends', 'private'])->default('public');
            $table->boolean('show_online_status')->default(true);
            $table->boolean('show_last_seen')->default(true);
            $table->boolean('allow_friend_requests')->default(true);
            $table->boolean('allow_messages')->default(true);

            // Status
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_online')->default(false);

            // Stats (denormalized for performance)
            $table->unsignedInteger('friends_count')->default(0);
            $table->unsignedInteger('followers_count')->default(0);
            $table->unsignedInteger('following_count')->default(0);
            $table->unsignedInteger('posts_count')->default(0);
            $table->unsignedInteger('profile_views_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
