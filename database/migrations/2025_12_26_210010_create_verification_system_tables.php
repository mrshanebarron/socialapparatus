<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verification requests
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['identity', 'creator', 'business', 'organization', 'government', 'notable'])->default('identity');
            $table->string('full_legal_name');
            $table->string('known_as')->nullable(); // Stage name, brand name, etc.
            $table->text('description'); // Why they should be verified
            $table->string('category')->nullable(); // Musician, Actor, Politician, etc.
            $table->json('documents'); // Uploaded verification documents
            $table->json('links')->nullable(); // External links proving identity
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'revoked'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        // Verified badges
        Schema::create('verified_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verification_request_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('badge_type', ['verified', 'creator', 'business', 'organization', 'government', 'notable'])->default('verified');
            $table->string('badge_color')->default('#1DA1F2'); // Badge color
            $table->string('badge_icon')->default('check'); // Icon name
            $table->string('display_name')->nullable(); // Verified name
            $table->boolean('is_active')->default(true);
            $table->timestamp('granted_at');
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'badge_type']);
            $table->index('is_active');
        });

        // Verification documents (secure storage)
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_request_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // id_front, id_back, selfie, business_license, etc.
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->boolean('is_verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->timestamps();

            $table->index('verification_request_id');
        });

        // Verification history/audit log
        Schema::create('verification_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Who took action
            $table->string('action'); // submitted, reviewed, approved, rejected, revoked
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['verification_request_id', 'created_at']);
        });

        // Add verification fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('profile_photo_path');
            $table->string('verification_badge_type')->nullable()->after('is_verified');
            $table->timestamp('verified_at')->nullable()->after('verification_badge_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verification_badge_type', 'verified_at']);
        });

        Schema::dropIfExists('verification_audit_logs');
        Schema::dropIfExists('verification_documents');
        Schema::dropIfExists('verified_badges');
        Schema::dropIfExists('verification_requests');
    }
};
