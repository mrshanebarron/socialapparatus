<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fact Check Labels
        Schema::create('fact_check_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // True, Mostly True, Mixed, Mostly False, False, Satire, Opinion
            $table->string('slug')->unique();
            $table->string('color');
            $table->string('icon')->nullable();
            $table->text('description');
            $table->integer('severity')->default(0); // 0 = neutral, higher = more concerning
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Content Verifications (fact checks applied to content)
        Schema::create('content_verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('verifiable'); // Post, Article, etc.
            $table->foreignId('fact_check_label_id')->constrained()->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('explanation');
            $table->json('sources')->nullable(); // Array of source URLs
            $table->string('status')->default('pending'); // pending, approved, disputed, removed
            $table->integer('disputes_count')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // Fact Check Disputes
        Schema::create('fact_check_disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_verification_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('reason');
            $table->json('evidence')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, accepted, rejected
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // Verified Sources (trusted fact-check organizations)
        Schema::create('verified_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default fact check labels
        \DB::table('fact_check_labels')->insert([
            ['name' => 'Verified', 'slug' => 'verified', 'color' => '#22c55e', 'description' => 'This information has been verified as accurate.', 'severity' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mostly True', 'slug' => 'mostly-true', 'color' => '#84cc16', 'description' => 'The main claim is accurate, but some details may be missing or slightly off.', 'severity' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mixed', 'slug' => 'mixed', 'color' => '#eab308', 'description' => 'Contains a mix of accurate and inaccurate information.', 'severity' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mostly False', 'slug' => 'mostly-false', 'color' => '#f97316', 'description' => 'The main claim is inaccurate, though some elements may be true.', 'severity' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'False', 'slug' => 'false', 'color' => '#ef4444', 'description' => 'This information has been verified as false.', 'severity' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Satire', 'slug' => 'satire', 'color' => '#8b5cf6', 'description' => 'This content is satire or parody and not meant to be taken literally.', 'severity' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Opinion', 'slug' => 'opinion', 'color' => '#6366f1', 'description' => 'This is an opinion piece and should not be treated as factual reporting.', 'severity' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('verified_sources');
        Schema::dropIfExists('fact_check_disputes');
        Schema::dropIfExists('content_verifications');
        Schema::dropIfExists('fact_check_labels');
    }
};
