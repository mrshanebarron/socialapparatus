<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Questions (Ask Me Anything)
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_public')->default(true); // Show on profile when answered
            $table->string('status')->default('pending'); // pending, answered, declined, hidden
            $table->timestamp('answered_at')->nullable();
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            $table->index(['recipient_id', 'status']);
        });

        // Question likes
        Schema::create('question_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['question_id', 'user_id']);
        });

        // Add AMA settings to profiles
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('allow_questions')->default(true)->after('cover_photo');
            $table->boolean('allow_anonymous_questions')->default(true)->after('allow_questions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_likes');
        Schema::dropIfExists('questions');

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['allow_questions', 'allow_anonymous_questions']);
        });
    }
};
