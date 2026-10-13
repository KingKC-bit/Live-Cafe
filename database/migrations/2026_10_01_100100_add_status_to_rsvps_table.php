<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cancelling an RSVP flips its status instead of deleting the row, so the
 * club keeps a record of every RSVP (Deliverable 1) and a member who cancels
 * and comes back re-activates the same row. The existing unique index on
 * (user_id, event_id) keeps blocking duplicate RSVPs.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->enum('status', ['going', 'cancelled'])->default('going')->after('extras');
            $table->timestamp('cancelled_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn(['status', 'cancelled_at']);
        });
    }
};
