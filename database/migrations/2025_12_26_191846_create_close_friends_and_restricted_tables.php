<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Close friends list
        Schema::create('close_friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'friend_id']);
        });

        // Restricted users list
        Schema::create('restricted_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restricted_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'restricted_id']);
        });

        // Add privacy settings to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_private')->default(false)->after('email');
            $table->boolean('require_follow_approval')->default(false)->after('is_private');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('close_friends');
        Schema::dropIfExists('restricted_users');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_private', 'require_follow_approval']);
        });
    }
};
