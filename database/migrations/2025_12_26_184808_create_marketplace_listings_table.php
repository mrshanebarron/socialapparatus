<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('marketplace_categories')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor'])->default('good');
            $table->json('images')->nullable();
            $table->string('location_name')->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->enum('status', ['active', 'pending', 'sold', 'expired', 'deleted'])->default('active');
            $table->enum('availability', ['in_stock', 'out_of_stock', 'single_item'])->default('single_item');
            $table->boolean('is_negotiable')->default(true);
            $table->boolean('is_shipping_available')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('saves_count')->default(0);
            $table->integer('messages_count')->default(0);
            $table->timestamp('featured_until')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
