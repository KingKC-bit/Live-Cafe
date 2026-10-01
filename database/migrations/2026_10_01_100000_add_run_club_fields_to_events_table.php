<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds what the run club pages need to describe a run or event.
 *
 * This is a new migration instead of an edit to create_events_table so that
 * teammates who already migrated only need `php artisan migrate`. Laravel
 * records every migration it has run and never runs the same file twice.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('title', 120)->default('')->after('id');
            $table->enum('type', ['run', 'event'])->default('run')->after('title');
            $table->decimal('distance_km', 5, 2)->nullable()->after('address');
            $table->string('pace', 40)->nullable()->after('distance_km');
            $table->string('dress_code', 120)->nullable()->after('pace');
            $table->string('sponsor', 120)->nullable()->after('dress_code');
            $table->enum('status', ['scheduled', 'cancelled'])->default('scheduled')->after('sponsor');
            $table->timestamp('cancelled_at')->nullable()->after('status');
        });

        // Events created before this migration have no title yet.
        DB::table('events')->where('title', '')->update(['title' => 'Club run']);
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'type',
                'distance_km',
                'pace',
                'dress_code',
                'sponsor',
                'status',
                'cancelled_at',
            ]);
        });
    }
};
