<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraiser_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundraiser_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable(); // For anonymous or non-user donations
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('payment_provider')->nullable();
            $table->string('payment_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamps();

            $table->index(['fundraiser_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraiser_donations');
    }
};
