<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Album collaborators
        Schema::create('album_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['viewer', 'contributor', 'editor', 'admin'])->default('contributor');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->boolean('can_add_photos')->default(true);
            $table->boolean('can_remove_photos')->default(false);
            $table->boolean('can_invite_others')->default(false);
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->unique(['album_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });

        // Album activity log
        Schema::create('album_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // added_photo, removed_photo, invited_user, etc.
            $table->nullableMorphs('subject'); // The item acted upon
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['album_id', 'created_at']);
        });

        // Album join requests (for public collaborative albums)
        Schema::create('album_join_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['album_id', 'user_id']);
        });

        // Add collaborative fields to albums table
        Schema::table('albums', function (Blueprint $table) {
            $table->boolean('is_collaborative')->default(false)->after('privacy');
            $table->enum('collaboration_type', ['invite_only', 'friends', 'public'])->default('invite_only')->after('is_collaborative');
            $table->integer('collaborator_count')->default(0)->after('collaboration_type');
            $table->integer('max_collaborators')->nullable()->after('collaborator_count');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn(['is_collaborative', 'collaboration_type', 'collaborator_count', 'max_collaborators']);
        });

        Schema::dropIfExists('album_join_requests');
        Schema::dropIfExists('album_activities');
        Schema::dropIfExists('album_collaborators');
    }
};
