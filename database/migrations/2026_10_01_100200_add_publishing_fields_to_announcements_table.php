<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Announcements get a title, an author, a pin and a published date.
 * An empty published_at means the announcement is still a draft.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('title', 150)->default('')->after('user_id');
            $table->boolean('is_pinned')->default(false)->after('description');
            $table->timestamp('published_at')->nullable()->after('is_pinned');
        });

        // Announcements created before this migration were already public.
        DB::table('announcements')->where('title', '')->update(['title' => 'Club update']);
        DB::table('announcements')->whereNull('published_at')->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['title', 'is_pinned', 'published_at']);
        });
    }
};
