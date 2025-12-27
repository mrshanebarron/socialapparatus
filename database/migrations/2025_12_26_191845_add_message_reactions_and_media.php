<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Message reactions table
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('emoji', 10);
            $table->timestamps();

            $table->unique(['message_id', 'user_id']);
        });

        // Add media fields to messages table (attachments already exists)
        Schema::table('messages', function (Blueprint $table) {
            $table->string('gif_url')->nullable()->after('attachments');
            $table->string('voice_note')->nullable()->after('gif_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reactions');

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['gif_url', 'voice_note']);
        });
    }
};
