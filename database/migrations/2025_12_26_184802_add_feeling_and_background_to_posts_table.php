<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('feeling')->nullable()->after('type'); // happy, sad, excited, etc.
            $table->string('activity')->nullable()->after('feeling'); // watching, listening, eating, etc.
            $table->string('activity_detail')->nullable()->after('activity'); // What they're watching/listening to
            $table->string('background_color')->nullable()->after('activity_detail'); // For text-only posts
            $table->string('background_gradient')->nullable()->after('background_color'); // gradient string
            $table->string('gif_url')->nullable()->after('background_gradient'); // GIF from Giphy/Tenor
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['feeling', 'activity', 'activity_detail', 'background_color', 'background_gradient', 'gif_url']);
        });
    }
};
