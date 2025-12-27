<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add threading support to comments (parent_id already exists)
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('depth')->default(0)->after('parent_id');
            $table->string('path')->nullable()->after('depth'); // Materialized path for efficient queries
            $table->integer('replies_count')->default(0)->after('likes_count');
        });

        // Create index for efficient nested comment queries
        Schema::table('comments', function (Blueprint $table) {
            $table->index('path');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['path']);
            $table->dropColumn(['depth', 'replies_count', 'path']);
        });
    }
};
