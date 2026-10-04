<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->time('event_time');

            $table->date('event_date');

            $table->string('address', 255);

            $table->text('description');

            $table->unsignedInteger('total_attendees')->default(0);

            $table->timestamps();

            $table->index([
                'event_date',
                'event_time',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
