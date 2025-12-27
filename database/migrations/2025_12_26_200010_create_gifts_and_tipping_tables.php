<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Virtual Gifts
        Schema::create('virtual_gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image'); // Gift icon/image
            $table->string('animation')->nullable(); // Animation file for display
            $table->integer('coin_cost'); // Cost in virtual coins
            $table->string('category')->default('general'); // general, birthday, love, celebration, etc.
            $table->boolean('is_animated')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('times_sent')->default(0);
            $table->timestamps();
        });

        // Sent Gifts
        Schema::create('sent_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_gift_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->morphs('giftable'); // Post, Profile, LiveStream, etc. (where gift appears)
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_seen')->default(false);
            $table->integer('coin_amount');
            $table->timestamps();

            $table->index(['recipient_id', 'created_at']);
        });

        // Tips (monetary support)
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->nullableMorphs('tippable'); // Post, LiveStream, Profile, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('payment_status')->default('pending'); // pending, completed, failed, refunded
            $table->string('payment_id')->nullable(); // External payment reference
            $table->timestamps();

            $table->index(['recipient_id', 'payment_status']);
        });

        // User Coin Balance
        Schema::create('coin_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('balance')->default(0);
            $table->integer('lifetime_earned')->default(0);
            $table->integer('lifetime_spent')->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });

        // Coin Transactions
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // purchase, gift_sent, gift_received, bonus, refund
            $table->integer('amount'); // Positive for credit, negative for debit
            $table->integer('balance_after');
            $table->text('description');
            $table->morphs('transactionable'); // Related model (SentGift, purchase, etc.)
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Coin Packages (for purchase)
        Schema::create('coin_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('coins');
            $table->integer('bonus_coins')->default(0);
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('USD');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add tipping settings to profiles
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('accept_tips')->default(false)->after('allow_anonymous_questions');
            $table->decimal('minimum_tip', 10, 2)->default(1.00)->after('accept_tips');
            $table->boolean('accept_gifts')->default(true)->after('minimum_tip');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['accept_tips', 'minimum_tip', 'accept_gifts']);
        });

        Schema::dropIfExists('coin_packages');
        Schema::dropIfExists('coin_transactions');
        Schema::dropIfExists('coin_balances');
        Schema::dropIfExists('tips');
        Schema::dropIfExists('sent_gifts');
        Schema::dropIfExists('virtual_gifts');
    }
};
