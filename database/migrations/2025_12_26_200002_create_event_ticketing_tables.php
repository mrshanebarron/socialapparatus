<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Event Ticket Types
        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // General Admission, VIP, etc.
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->nullable(); // null = unlimited
            $table->integer('sold_count')->default(0);
            $table->integer('max_per_order')->default(10);
            $table->timestamp('sales_start_at')->nullable();
            $table->timestamp('sales_end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Event Tickets (purchased tickets)
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_ticket_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ticket_code')->unique();
            $table->string('qr_code')->nullable();
            $table->string('status')->default('valid'); // valid, used, cancelled, refunded
            $table->string('attendee_name')->nullable();
            $table->string('attendee_email')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['event_id', 'status']);
        });

        // Event Waitlist
        Schema::create('event_waitlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_ticket_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->string('status')->default('waiting'); // waiting, offered, accepted, expired
            $table->timestamp('offered_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });

        // Add ticketing fields to events table
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('requires_ticket')->default(false)->after('is_online');
            $table->integer('capacity')->nullable()->after('requires_ticket');
            $table->boolean('waitlist_enabled')->default(false)->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['requires_ticket', 'capacity', 'waitlist_enabled']);
        });

        Schema::dropIfExists('event_waitlist');
        Schema::dropIfExists('event_tickets');
        Schema::dropIfExists('event_ticket_types');
    }
};
