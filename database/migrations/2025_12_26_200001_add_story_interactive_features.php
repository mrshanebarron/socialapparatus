<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Story Polls
        Schema::create('story_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->json('options'); // Array of poll options
            $table->boolean('allow_multiple')->default(false);
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // Story Poll Votes
        Schema::create('story_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('option_index'); // Which option they voted for
            $table->timestamps();

            $table->unique(['story_poll_id', 'user_id', 'option_index']);
        });

        // Story Questions (Ask Me Anything in stories)
        Schema::create('story_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->string('prompt'); // "Ask me anything", etc.
            $table->boolean('allow_anonymous')->default(true);
            $table->timestamps();
        });

        // Story Question Responses
        Schema::create('story_question_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('response');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_featured')->default(false); // Owner can feature responses
            $table->timestamps();
        });

        // Story Sliders (emoji slider ratings)
        Schema::create('story_sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->string('emoji')->default('🔥');
            $table->timestamps();
        });

        // Story Slider Responses
        Schema::create('story_slider_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_slider_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('value'); // 0-100 scale
            $table->timestamps();

            $table->unique(['story_slider_id', 'user_id']);
        });

        // Story Quizzes
        Schema::create('story_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->json('options'); // Array of options
            $table->integer('correct_option_index');
            $table->timestamps();
        });

        // Story Quiz Answers
        Schema::create('story_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('selected_option_index');
            $table->boolean('is_correct');
            $table->timestamps();

            $table->unique(['story_quiz_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_quiz_answers');
        Schema::dropIfExists('story_quizzes');
        Schema::dropIfExists('story_slider_responses');
        Schema::dropIfExists('story_sliders');
        Schema::dropIfExists('story_question_responses');
        Schema::dropIfExists('story_questions');
        Schema::dropIfExists('story_poll_votes');
        Schema::dropIfExists('story_polls');
    }
};
