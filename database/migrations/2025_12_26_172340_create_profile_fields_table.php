<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Custom profile fields that admins can create
        Schema::create('profile_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Internal name
            $table->string('label'); // Display label
            $table->text('description')->nullable();
            $table->enum('type', [
                'text',
                'textarea',
                'select',
                'multiselect',
                'checkbox',
                'date',
                'url',
                'email',
                'phone',
                'number'
            ])->default('text');
            $table->json('options')->nullable(); // For select/multiselect types
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_visible_in_registration')->default(false);
            $table->boolean('is_visible_in_profile')->default(true);
            $table->boolean('is_searchable')->default(false);
            $table->enum('visibility', ['public', 'friends', 'private'])->default('public');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_fields');
    }
};
