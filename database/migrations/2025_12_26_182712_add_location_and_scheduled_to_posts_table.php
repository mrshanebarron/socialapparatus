<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('location_name')->nullable()->after('visibility');
            $table->decimal('location_lat', 10, 8)->nullable()->after('location_name');
            $table->decimal('location_lng', 11, 8)->nullable()->after('location_lat');
            $table->timestamp('scheduled_at')->nullable()->after('location_lng');
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('published')->after('scheduled_at');
            $table->timestamp('edited_at')->nullable()->after('status');
            $table->boolean('is_edited')->default(false)->after('edited_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['location_name', 'location_lat', 'location_lng', 'scheduled_at', 'status', 'edited_at', 'is_edited']);
        });
    }
};
