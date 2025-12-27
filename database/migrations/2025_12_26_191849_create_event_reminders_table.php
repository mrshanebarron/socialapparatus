<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('remind_before'); // 15m, 30m, 1h, 1d, 1w
            $table->timestamp('remind_at');
            $table->boolean('is_sent')->default(false);
            $table->string('notification_type')->default('both'); // email, push, both
            $table->timestamps();

            $table->unique(['event_id', 'user_id', 'remind_before']);
        });

        // Add recurring event support
        Schema::table('events', function (Blueprint $table) {
            $table->string('recurrence_type')->nullable()->after('ends_at'); // daily, weekly, monthly, yearly
            $table->json('recurrence_days')->nullable()->after('recurrence_type'); // for weekly: [1,3,5] = Mon,Wed,Fri
            $table->date('recurrence_end_date')->nullable()->after('recurrence_days');
            $table->foreignId('parent_event_id')->nullable()->after('recurrence_end_date')->constrained('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_reminders');

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropColumn(['recurrence_type', 'recurrence_days', 'recurrence_end_date', 'parent_event_id']);
        });
    }
};
