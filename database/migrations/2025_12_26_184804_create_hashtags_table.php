<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->unique();
            $table->integer('posts_count')->default(0);
            $table->integer('weekly_count')->default(0);
            $table->integer('daily_count')->default(0);
            $table->timestamps();

            $table->index('posts_count');
            $table->index('weekly_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hashtags');
    }
};
