<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // User's values for custom profile fields
        Schema::create('profile_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('profile_field_id')->constrained()->onDelete('cascade');
            $table->text('value')->nullable();
            $table->enum('visibility', ['public', 'friends', 'private'])->nullable(); // Override field default
            $table->timestamps();

            $table->unique(['profile_id', 'profile_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_field_values');
    }
};
