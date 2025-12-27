<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Watch parties
        Schema::create('watch_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url'); // YouTube, Vimeo, etc.
            $table->string('video_provider')->nullable(); // youtube, vimeo, etc.
            $table->string('video_thumbnail')->nullable();
            $table->enum('status', ['scheduled', 'live', 'paused', 'ended'])->default('scheduled');
            $table->enum('privacy', ['public', 'friends', 'group', 'invite_only'])->default('friends');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('current_time_seconds')->default(0);
            $table->boolean('is_playing')->default(false);
            $table->integer('max_participants')->nullable();
            $table->integer('participant_count')->default(0);
            $table->timestamps();

            $table->index(['host_id', 'status']);
            $table->index('scheduled_at');
        });

        // Watch party participants
        Schema::create('watch_party_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watch_party_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['invited', 'joined', 'left', 'kicked'])->default('invited');
            $table->boolean('is_host')->default(false);
            $table->boolean('can_control')->default(false); // Can control playback
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->unique(['watch_party_id', 'user_id']);
            $table->index(['watch_party_id', 'status']);
        });

        // Watch party chat messages
        Schema::create('watch_party_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watch_party_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->integer('video_timestamp')->nullable(); // Time in video when sent
            $table->string('type')->default('message'); // message, reaction, system
            $table->timestamps();

            $table->index(['watch_party_id', 'created_at']);
        });

        // Watch party invitations
        Schema::create('watch_party_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watch_party_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inviter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('invitee_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();

            $table->unique(['watch_party_id', 'invitee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_party_invitations');
        Schema::dropIfExists('watch_party_messages');
        Schema::dropIfExists('watch_party_participants');
        Schema::dropIfExists('watch_parties');
    }
};
