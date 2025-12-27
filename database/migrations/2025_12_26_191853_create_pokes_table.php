<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('poked_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_seen')->default(false);
            $table->timestamp('poked_back_at')->nullable();
            $table->timestamps();

            $table->index(['poked_id', 'is_seen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokes');
    }
};
